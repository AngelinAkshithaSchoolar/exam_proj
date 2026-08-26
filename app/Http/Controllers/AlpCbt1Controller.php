<?php

namespace App\Http\Controllers;

use App\Data\AlpCbt1Bank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * ─────────────────────────────────────────────────────────────────────────────
 *  RRB ALP CBT 1 — mock test flow
 * ─────────────────────────────────────────────────────────────────────────────
 *  general → instructions → test → submit → result → review
 *
 *  Two deliberate design decisions, both worth keeping:
 *
 *  1. THE ANSWER KEY NEVER REACHES THE BROWSER.
 *     The test page is fed AlpCbt1Bank::forPlayer(), which has no 'a' key.
 *     Scoring happens here, in submit(), against AlpCbt1Bank::answerKey().
 *     "View source" gets a student nothing.
 *
 *  2. THE CLOCK IS THE SERVER'S, NOT THE BROWSER'S.
 *     started_at is stamped in the session when the candidate clicks
 *     "I am ready to begin". The countdown on screen is only a display of
 *     the remaining seconds the server handed it; deadlineReached() is what
 *     actually decides whether a late submission still counts. Editing the
 *     JS timer buys no extra time.
 *
 *  Attempt state lives in the session, so this runs with no migrations and
 *  no database rows. When you are ready to keep history, swap the
 *  $this->attempt() / $this->store() pair for an Attempt model — nothing
 *  else in this class has to change.
 * ─────────────────────────────────────────────────────────────────────────────
 */
class AlpCbt1Controller extends Controller
{
    private const SESSION_KEY = 'alp_cbt1_attempt';

    /** Grace period (seconds) allowed for the submit request to travel. */
    private const NETWORK_GRACE = 15;

    /* ───────────────────────────── Screens ───────────────────────────── */

    /** Screen 1 — the generic "how the CBT software works" page. */
    public function general(): View
    {
        return view('mock-tests.alp-cbt1.general-instructions', [
            'candidate' => $this->candidateName(),
        ]);
    }

    /** Screen 2 — test-specific instructions, language picker, declaration. */
    public function instructions(): View
    {
        return view('mock-tests.alp-cbt1.instructions', [
            'candidate'     => $this->candidateName(),
            'total'         => AlpCbt1Bank::total(),
            'duration'      => AlpCbt1Bank::DURATION_MINUTES,
            'sectionCounts' => AlpCbt1Bank::sectionCounts(),
            'sections'      => AlpCbt1Bank::SECTIONS,
            'negative'      => AlpCbt1Bank::MARK_NEGATIVE,
        ]);
    }

    /** Stamps the start time and sends the candidate into the test. */
    public function start(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lang'    => ['required', 'in:en,hi'],
            'declare' => ['accepted'],
        ], [
            'declare.accepted' => 'You must agree to the instructions before starting.',
            'lang.required'    => 'Please choose your default language.',
        ]);

        session([self::SESSION_KEY => [
            'started_at'   => now()->toIso8601String(),
            'lang'         => $data['lang'],
            'answers'      => [],      // question index => chosen option index
            'marked'       => [],      // question indexes flagged for review
            'visited'      => [],
            'submitted_at' => null,
            'result'       => null,
        ]]);

        return redirect()->route('alp-cbt1.test');
    }

    /** Screen 3 — the test window itself. */
    public function test(): View|RedirectResponse
    {
        $attempt = $this->attempt();

        if (! $attempt) {
            return redirect()->route('alp-cbt1.general')
                ->with('exam_notice', 'Start the test from the instructions page.');
        }

        // Already submitted? Send them to the result rather than back in.
        if ($attempt['submitted_at']) {
            return redirect()->route('alp-cbt1.result');
        }

        // Walked away and came back after the clock ran out — auto-submit.
        if ($this->remainingSeconds($attempt) <= 0) {
            $this->score($attempt, autoSubmitted: true);

            return redirect()->route('alp-cbt1.result');
        }

        return view('mock-tests.alp-cbt1.test', [
            'questions'     => AlpCbt1Bank::forPlayer(),
            'sections'      => AlpCbt1Bank::SECTIONS,
            'ranges'        => AlpCbt1Bank::sectionRanges(),
            'total'         => AlpCbt1Bank::total(),
            'remaining'     => $this->remainingSeconds($attempt),
            'lang'          => $attempt['lang'],
            'saved'         => $attempt['answers'],
            'markedSaved'   => $attempt['marked'],
            'candidate'     => $this->candidateName(),
        ]);
    }

    /**
     * Receives the whole answer sheet in one POST and scores it.
     *
     * Sent as JSON so a 75-answer payload does not have to be flattened into
     * form fields, and so the page can fire it from the auto-submit path
     * without building a form.
     */
    public function submit(Request $request): RedirectResponse
    {
        $attempt = $this->attempt();

        if (! $attempt) {
            return redirect()->route('alp-cbt1.general');
        }

        if ($attempt['submitted_at']) {
            return redirect()->route('alp-cbt1.result');
        }

        $data = $request->validate([
            'answers'   => ['array'],
            'answers.*' => ['nullable', 'integer', 'between:0,3'],
            'marked'    => ['array'],
            'marked.*'  => ['integer', 'min:0'],
        ]);

        $late = $this->remainingSeconds($attempt) < -self::NETWORK_GRACE;

        // Keep only keys that are real question indexes.
        $answers = [];
        foreach (($data['answers'] ?? []) as $index => $choice) {
            $index = (int) $index;
            if ($choice !== null && $index >= 0 && $index < AlpCbt1Bank::total()) {
                $answers[$index] = (int) $choice;
            }
        }

        $attempt['answers'] = $answers;
        $attempt['marked']  = array_values(array_unique($data['marked'] ?? []));

        $this->score($attempt, autoSubmitted: $late);

        return redirect()->route('alp-cbt1.result');
    }

    /** Screen 4 — the score card. */
    public function result(): View|RedirectResponse
    {
        $attempt = $this->attempt();

        if (! $attempt || ! $attempt['submitted_at']) {
            return redirect()->route('alp-cbt1.general');
        }

        return view('mock-tests.alp-cbt1.result', [
            'r'         => $attempt['result'],
            'sections'  => AlpCbt1Bank::SECTIONS,
            'qualify'   => AlpCbt1Bank::QUALIFYING,
            'duration'  => AlpCbt1Bank::DURATION_MINUTES,
            'lang'      => $attempt['lang'],
            'active'    => 'mock-history',
        ]);
    }

    /** Screen 5 — question-by-question review, now that answers may be shown. */
    public function review(): View|RedirectResponse
    {
        $attempt = $this->attempt();

        if (! $attempt || ! $attempt['submitted_at']) {
            return redirect()->route('alp-cbt1.general');
        }

        return view('mock-tests.alp-cbt1.review', [
            'questions' => AlpCbt1Bank::all(),
            'sections'  => AlpCbt1Bank::SECTIONS,
            'answers'   => $attempt['answers'],
            'lang'      => $attempt['lang'],
            'r'         => $attempt['result'],
            'active'    => 'mock-history',
        ]);
    }

    /** Clears the attempt so the candidate can sit the test again. */
    public function retake(): RedirectResponse
    {
        session()->forget(self::SESSION_KEY);

        return redirect()->route('alp-cbt1.general');
    }

    /**
     * Lightweight endpoint the test page pings so a candidate who reloads
     * mid-test does not lose their sheet. Fire-and-forget: a failure here
     * must never interrupt the exam.
     */
    public function autosave(Request $request): \Illuminate\Http\JsonResponse
    {
        $attempt = $this->attempt();

        if (! $attempt || $attempt['submitted_at']) {
            return response()->json(['ok' => false], 409);
        }

        $data = $request->validate([
            'answers'   => ['array'],
            'answers.*' => ['nullable', 'integer', 'between:0,3'],
            'marked'    => ['array'],
            'marked.*'  => ['integer', 'min:0'],
        ]);

        $attempt['answers'] = array_filter(
            $data['answers'] ?? [],
            static fn ($v) => $v !== null
        );
        $attempt['marked'] = array_values(array_unique($data['marked'] ?? []));

        $this->store($attempt);

        return response()->json([
            'ok'        => true,
            'remaining' => $this->remainingSeconds($attempt),
        ]);
    }

    /* ───────────────────────────── Internals ───────────────────────────── */

    /** @return array<string,mixed>|null */
    private function attempt(): ?array
    {
        return session(self::SESSION_KEY);
    }

    /** @param array<string,mixed> $attempt */
    private function store(array $attempt): void
    {
        session([self::SESSION_KEY => $attempt]);
    }

    /**
     * Seconds left on the server's clock. Negative once the window has closed.
     *
     * @param array<string,mixed> $attempt
     */
    private function remainingSeconds(array $attempt): int
    {
        $deadline = Carbon::parse($attempt['started_at'])
            ->addMinutes(AlpCbt1Bank::DURATION_MINUTES);

        return (int) round(now()->diffInSeconds($deadline, false));
    }

    /**
     * Marks the attempt, writes the result into the session and returns it.
     *
     * @param  array<string,mixed>  $attempt
     * @return array<string,mixed>
     */
    private function score(array $attempt, bool $autoSubmitted = false): array
    {
        $key       = AlpCbt1Bank::answerKey();
        $questions = AlpCbt1Bank::all();
        $answers   = $attempt['answers'] ?? [];

        $correct = $wrong = 0;

        // Per-section tallies, keyed the same way as AlpCbt1Bank::SECTIONS.
        $bySection = [];
        foreach (array_keys(AlpCbt1Bank::SECTIONS) as $s) {
            $bySection[$s] = ['total' => 0, 'correct' => 0, 'wrong' => 0, 'skipped' => 0, 'score' => 0.0];
        }

        foreach ($questions as $i => $q) {
            $s = $q['s'];
            $bySection[$s]['total']++;

            if (! array_key_exists($i, $answers)) {
                $bySection[$s]['skipped']++;

                continue;
            }

            if ($answers[$i] === $key[$i]) {
                $correct++;
                $bySection[$s]['correct']++;
            } else {
                $wrong++;
                $bySection[$s]['wrong']++;
            }
        }

        foreach ($bySection as $s => $row) {
            $bySection[$s]['score'] = round(
                $row['correct'] * AlpCbt1Bank::MARK_CORRECT - $row['wrong'] * AlpCbt1Bank::MARK_NEGATIVE,
                2
            );
        }

        $total     = AlpCbt1Bank::total();
        $attempted = $correct + $wrong;
        $penalty   = round($wrong * AlpCbt1Bank::MARK_NEGATIVE, 2);
        $score     = round($correct * AlpCbt1Bank::MARK_CORRECT - $penalty, 2);

        $startedAt = Carbon::parse($attempt['started_at']);
        $secondsUsed = min(
            (int) round($startedAt->diffInSeconds(now())),
            AlpCbt1Bank::DURATION_MINUTES * 60
        );

        $attempt['submitted_at'] = now()->toIso8601String();
        $attempt['result'] = [
            'score'          => $score,
            'max'            => (float) $total,
            'percentage'     => $total > 0 ? round($score / $total * 100, 2) : 0.0,
            'correct'        => $correct,
            'wrong'          => $wrong,
            'attempted'      => $attempted,
            'skipped'        => $total - $attempted,
            'total'          => $total,
            'penalty'        => $penalty,
            // Accuracy is out of what was attempted — not out of 75. A
            // candidate who answers 10 and gets 9 right is 90% accurate,
            // even though they scored 9/75.
            'accuracy'       => $attempted > 0 ? round($correct / $attempted * 100, 2) : 0.0,
            'seconds_used'   => $secondsUsed,
            'speed'          => $secondsUsed > 0 ? round($attempted / ($secondsUsed / 60), 2) : 0.0,
            'by_section'     => $bySection,
            'auto_submitted' => $autoSubmitted,
            'answers'        => $answers,
        ];

        $this->store($attempt);

        return $attempt['result'];
    }

    /**
     * Whoever is sitting the test. Swap for auth()->user()->name once the
     * app has real accounts — the sidebar currently hard-codes this too.
     */
    private function candidateName(): string
    {
        return auth()->check() ? auth()->user()->name : 'Angelin';
    }
}
