<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Sidebar links that don't have a page yet all land here.
     * The slug decides the title/blurb so one view covers all of them.
     */
    private const FEATURES = [
        'study-plan'   => ['📅', 'Study Plan',   'Your day-by-day plan to hit your target score.'],
        'vocabulary'   => ['🔤', 'Vocabulary',   'Word lists, flashcards and spaced repetition.'],
        'performance'  => ['📈', 'Performance',  'Deep analytics on every skill you practise.'],
        'resources'    => ['📎', 'Resources',    'Templates, cheat sheets and downloadable notes.'],
        'mock-history' => ['🕘', 'Mock History', 'Every mock test you have taken, side by side.'],
        'community'    => ['👥', 'Community',    'Study groups, discussion boards and peer tips.'],
        'achievements' => ['🏆', 'Achievements', 'Badges, streaks and milestones you have unlocked.'],
        'ai-tutor'     => ['✨', 'AI Tutor',     'Mira, your always-on AI speaking and writing coach.'],
        'mock-tests'   => ['📋', 'My Tests',     'Full-length and sectional mock tests.'],

        // Slugs linked from the Mock Test History page
        'sectional-tests'      => ['🎯', 'Sectional Tests',      'Practise one section at a time under exam conditions.'],
        'topic-tests'          => ['📚', 'Topic Tests',          'Short tests focused on a single topic.'],
        'previous-year-papers' => ['🗂️', 'Previous Year Papers', 'Real papers from past exam sittings.'],
        'custom-tests'         => ['🛠️', 'Custom Tests',         'Build your own test from any mix of topics.'],
        'bookmarks'            => ['🔖', 'Bookmarks',            'Every question you have saved for later.'],
        'notes'                => ['📝', 'Notes',                'Your own notes, organised by subject.'],
        'review-test'          => ['🔍', 'Review Test',          'Question-by-question review of a completed attempt.'],
        'view-solutions'       => ['💡', 'Solutions',            'Worked solutions and explanations for every question.'],
        're-attempt'           => ['🔁', 'Re-attempt',           'Retake a test you have already completed.'],
        'share-result'         => ['📤', 'Share Result',         'Share a score card with a mentor or study group.'],
    ];

    public function comingSoon(string $feature = 'study-plan')
    {
        [$icon, $title, $blurb] = self::FEATURES[$feature]
            ?? ['🚧', ucwords(str_replace('-', ' ', $feature)), 'This section is still being built.'];

        return view('coming-soon', compact('icon', 'title', 'blurb', 'feature'));
    }

    public function mockTests()
    {
        return $this->comingSoon('mock-tests');
    }

    public function profile()
    {
        return view('profile');
    }

    /**
     * The sidebar has a Log Out button on every page. There is no auth
     * scaffolding in this project yet, so this just clears the demo session
     * (progress, bookmarks, streaks) and sends you back to the dashboard.
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('status', 'Signed out — demo data reset.');
    }
}
