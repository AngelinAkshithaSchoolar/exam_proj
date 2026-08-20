<?php

namespace App\Http\Controllers;

use App\Support\PracticeData;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    /**
     * GET /practice — render the practice dashboard.
     */
    public function index()
    {
        PracticeData::boot();

        $macroSkills = PracticeData::macroSkills();
        $weekly      = session('weekly', [0, 0, 0, 0, 0, 0, 0]);

        return view('practice', [
            'questionTypes'       => PracticeData::questionTypes(),
            'macroSkills'         => $macroSkills,
            'weekDays'            => PracticeData::weekDays(),
            'questionBank'        => PracticeData::questionBank(),
            'sampleNotifications' => PracticeData::sampleNotifications(),
            'overallAccuracy'     => PracticeData::overallAccuracy(),
            'weeklyTotal'         => array_sum($weekly),
            'weeklyMax'           => max(1, max($weekly)),
            'trend'               => session('trend', []),
            'trendMin'            => 0,
            'trendMax'            => 100,
        ]);
    }

    /**
     * POST /practice — the AJAX endpoint the page's JS talks to.
     * Replaces the $_POST['action'] switch from the original single-file PHP.
     */
    public function api(Request $request)
    {
        PracticeData::boot();

        return match ($request->input('action')) {
            'submit_quiz'            => response()->json($this->submitQuiz($request)),
            'toggle_bookmark'        => response()->json($this->toggleBookmark($request)),
            'toggle_streak_day'      => response()->json($this->toggleStreakDay($request)),
            'mark_notifications_read'=> response()->json($this->markNotificationsRead()),
            'save_full_score'        => response()->json($this->saveFullScore($request)),
            'save_sectional_score'   => response()->json($this->saveSectionalScore($request)),
            'reset_progress'         => response()->json($this->resetProgress()),
            default                  => response()->json(['ok' => false, 'error' => 'unknown action'], 400),
        };
    }

    private function submitQuiz(Request $request): array
    {
        $results = json_decode($request->input('results', '[]'), true);

        if (is_array($results)) {
            $typeStats  = session('type_stats', []);
            $macroStats = session('macro_stats', []);
            $recent     = session('recent', []);
            $weekly     = session('weekly', [0, 0, 0, 0, 0, 0, 0]);
            $trend      = session('trend', []);

            foreach ($results as $r) {
                $type    = $r['type'] ?? '';
                $correct = (int) ($r['correct'] ?? 0);
                $total   = (int) ($r['total'] ?? 0);

                if (! isset($typeStats[$type]) || $total <= 0) {
                    continue;
                }

                $typeStats[$type]['correct'] += $correct;
                $typeStats[$type]['total']   += $total;

                $macro = PracticeData::macroFor($type);
                if ($macro && isset($macroStats[$macro])) {
                    $macroStats[$macro]['correct'] += $correct;
                    $macroStats[$macro]['total']   += $total;
                }

                array_unshift($recent, [
                    'type'     => $type,
                    'name'     => PracticeData::labelFor($type),
                    'count'    => $total,
                    'accuracy' => PracticeData::pct($correct, $total),
                ]);
                $recent = array_slice($recent, 0, 6);

                $today = (int) now()->dayOfWeekIso - 1; // 0 = Monday
                $weekly[$today] += $total;

                $trend[] = PracticeData::pct($correct, $total);
                $trend   = array_slice($trend, -10);
            }

            session()->put([
                'type_stats'  => $typeStats,
                'macro_stats' => $macroStats,
                'recent'      => $recent,
                'weekly'      => $weekly,
                'trend'       => $trend,
            ]);
        }

        $typeOut = [];
        foreach (PracticeData::questionTypes() as $t) {
            $typeOut[$t['id']] = PracticeData::typeAccuracy($t['id']);
        }

        $macroOut = [];
        foreach (array_keys(PracticeData::macroSkills()) as $k) {
            $macroOut[$k] = PracticeData::macroAccuracy($k);
        }

        return [
            'ok'      => true,
            'types'   => $typeOut,
            'macro'   => $macroOut,
            'overall' => PracticeData::overallAccuracy(),
            'recent'  => session('recent'),
            'weekly'  => session('weekly'),
            'trend'   => session('trend'),
        ];
    }

    private function toggleBookmark(Request $request): array
    {
        $type     = (string) $request->input('type', '');
        $question = (string) $request->input('question', '');
        $key      = $type.'|'.$question;

        $bookmarks = session('bookmarks', []);
        $found     = false;

        foreach ($bookmarks as $i => $b) {
            if ($b['key'] === $key) {
                array_splice($bookmarks, $i, 1);
                $found = true;
                break;
            }
        }

        if (! $found) {
            $bookmarks[] = [
                'key'       => $key,
                'type'      => $type,
                'type_name' => PracticeData::labelFor($type),
                'question'  => $question,
            ];
        }

        session()->put('bookmarks', $bookmarks);

        return ['ok' => true, 'bookmarked' => ! $found, 'bookmarks' => $bookmarks];
    }

    private function toggleStreakDay(Request $request): array
    {
        $idx  = (int) $request->input('idx', -1);
        $days = session('streak_days', array_fill(0, 7, false));

        if ($idx >= 0 && $idx < 7) {
            $days[$idx] = ! $days[$idx];
            session()->put('streak_days', $days);
            session()->put('streak_count', count(array_filter($days)));
        }

        return ['ok' => true, 'days' => session('streak_days'), 'count' => session('streak_count')];
    }

    private function markNotificationsRead(): array
    {
        session()->put('unread_notifications', 0);

        return ['ok' => true];
    }

    private function saveFullScore(Request $request): array
    {
        $last = [
            'score' => (int) $request->input('score', 0),
            'total' => (int) $request->input('total', 90),
            'date'  => now()->format('d M Y'),
        ];

        session()->put('last_full_score', $last);

        return ['ok' => true, 'last' => $last];
    }

    private function saveSectionalScore(Request $request): array
    {
        $last = session('last_sectional', ['score' => 0, 'total' => 90, 'attempts' => 0]);

        $last['score']     = (int) $request->input('score', 0);
        $last['total']     = (int) $request->input('total', 90);
        $last['attempts'] += 1;

        session()->put('last_sectional', $last);

        return ['ok' => true, 'last' => $last];
    }

    private function resetProgress(): array
    {
        PracticeData::reset();

        return ['ok' => true];
    }
}
