<?php

namespace App\Support;

/**
 * All static data + progress maths for the Practice page.
 *
 * Converted from the original single-file practice.php. The data arrays are
 * unchanged; the only real difference is that progress now lives in Laravel's
 * session instead of $_SESSION, so it works with Laravel's session middleware.
 */
class PracticeData
{
    public static function questionTypes(): array
    {
        return [
    ['id' => 'read_aloud',        'name' => 'Read Aloud',                    'icon' => '📖', 'color' => 'blue',   'count' => 60,  'macro' => 'speaking'],
    ['id' => 'repeat_sentence',   'name' => 'Repeat Sentence',                'icon' => '🎙️', 'color' => 'green',  'count' => 80,  'macro' => 'speaking'],
    ['id' => 'describe_image',    'name' => 'Describe Image',                 'icon' => '🖼️', 'color' => 'orange', 'count' => 40,  'macro' => 'speaking'],
    ['id' => 'retell_lecture',    'name' => 'Re-tell Lecture',                'icon' => '🎧', 'color' => 'indigo', 'count' => 50,  'macro' => 'speaking'],
    ['id' => 'answer_short',      'name' => 'Answer Short Question',          'icon' => '❓', 'color' => 'rose',   'count' => 100, 'macro' => 'speaking'],
    ['id' => 'summarize_spoken',  'name' => 'Summarize Spoken Text',          'icon' => '📝', 'color' => 'teal',   'count' => 40,  'macro' => 'listening'],
    ['id' => 'mcq_single',        'name' => 'Multiple Choice, Single Answer', 'icon' => '◉',  'color' => 'amber',  'count' => 120, 'macro' => 'listening'],
    ['id' => 'mcq_multiple',      'name' => 'Multiple Choice, Multiple Answer','icon' => '☑️', 'color' => 'violet', 'count' => 80,  'macro' => 'reading'],
    ['id' => 'fill_blanks',       'name' => 'Fill in the Blanks',             'icon' => '▭',  'color' => 'pink',   'count' => 60,  'macro' => 'reading'],
    ['id' => 'highlight_summary', 'name' => 'Highlight Correct Summary',      'icon' => '🖊️', 'color' => 'cyan',   'count' => 40,  'macro' => 'listening'],
];
    }

    public static function defaultTypeAccuracy(): array
    {
        return [
    'read_aloud' => 72, 'repeat_sentence' => 65, 'describe_image' => 71, 'retell_lecture' => 68,
    'answer_short' => 75, 'summarize_spoken' => 63, 'mcq_single' => 69, 'mcq_multiple' => 61,
    'fill_blanks' => 62, 'highlight_summary' => 66,
];
    }

    public static function macroSkills(): array
    {
        return [
    'speaking'  => ['label' => 'Speaking', 'icon' => '🎤', 'accuracy' => 72, 'delta' => 8],
    'listening' => ['label' => 'Listening', 'icon' => '🎧', 'accuracy' => 65, 'delta' => 5],
    'reading'   => ['label' => 'Reading',   'icon' => '📗', 'accuracy' => 61, 'delta' => -4],
    'writing'   => ['label' => 'Writing',   'icon' => '✏️', 'accuracy' => 74, 'delta' => 10],
];
    }

    public static function weekDays(): array
    {
        return ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
    }

    public static function questionBank(): array
    {
        return [
    'read_aloud' => [
        ['q' => 'Which word carries the primary stress in "photograph"?', 'options' => ['pho', 'to', 'graph', 'All equal'], 'answer' => 0],
        ['q' => 'Read Aloud is mainly scored on:', 'options' => ['Handwriting', 'Pronunciation & fluency', 'Vocabulary range', 'Spelling'], 'answer' => 1],
        ['q' => 'Which is the best pause point? "The results, / which were published yesterday, / surprised everyone."', 'options' => ['No pauses needed', 'At the commas', 'Only at the period', 'Every 3 words'], 'answer' => 1],
        ['q' => 'A rising intonation at the end of a statement usually signals:', 'options' => ['A command', 'A question', 'A list', 'Nothing'], 'answer' => 1],
        ['q' => 'Ideal reading pace for Read Aloud is closest to:', 'options' => ['Very slow, word by word', 'Natural conversational pace', 'As fast as possible', 'Whispered'], 'answer' => 1],
    ],
    'repeat_sentence' => [
        ['q' => 'Repeat Sentence mainly tests:', 'options' => ['Reading speed', 'Listening & short-term memory', 'Spelling', 'Grammar writing'], 'answer' => 1],
        ['q' => 'Best strategy if you miss a word in the middle:', 'options' => ['Stop completely', 'Keep the sentence structure and continue', 'Repeat from the start', 'Say "pass"'], 'answer' => 1],
        ['q' => 'Sentences in this task are typically:', 'options' => ['1 word', '3–4 words', '5–20 words', '50+ words'], 'answer' => 2],
        ['q' => 'You should start speaking:', 'options' => ['Immediately after the beep', 'After 10 seconds of silence', 'Only when asked twice', 'Never'], 'answer' => 0],
        ['q' => 'Which skill improves Repeat Sentence the most?', 'options' => ['Active listening practice', 'Reading novels only', 'Watching silent films', 'Memorizing dictionaries'], 'answer' => 0],
    ],
    'describe_image' => [
        ['q' => 'A strong Describe Image answer usually opens with:', 'options' => ['A joke', 'An overview of the image type', 'The smallest detail first', 'Silence'], 'answer' => 1],
        ['q' => 'Which phrase is useful to start describing a chart?', 'options' => ['"Once upon a time"', '"The graph illustrates..."', '"In my opinion, cats are..."', '"The end."'], 'answer' => 1],
        ['q' => 'Time typically given to prepare before speaking is around:', 'options' => ['0 seconds', '5 seconds', '25 seconds', '5 minutes'], 'answer' => 2],
        ['q' => 'A good closing line for this task is:', 'options' => ['Trailing off mid-sentence', 'A brief concluding remark or trend summary', 'Repeating the title only', 'Asking a question'], 'answer' => 1],
        ['q' => 'Which is least useful when describing a bar chart?', 'options' => ['Mentioning the highest value', 'Mentioning the trend', 'Describing unrelated memories', 'Comparing categories'], 'answer' => 2],
    ],
    'retell_lecture' => [
        ['q' => 'Re-tell Lecture is best approached by:', 'options' => ['Memorizing every word', 'Noting key points while listening', 'Ignoring the audio', 'Guessing randomly'], 'answer' => 1],
        ['q' => 'A good structure for your retelling is:', 'options' => ['Random order', 'Topic → main points → conclusion', 'Only the conclusion', 'Only examples'], 'answer' => 1],
        ['q' => 'Note-taking during the lecture should be:', 'options' => ['Full sentences', 'Keywords & short phrases', 'Nothing written', 'Only numbers'], 'answer' => 1],
        ['q' => 'If the lecture includes a graph, you should:', 'options' => ['Ignore it', 'Mention what it shows briefly', 'Describe the colors only', 'Skip your turn'], 'answer' => 1],
        ['q' => 'The goal of this task is mainly to test:', 'options' => ['Comprehension + spoken summary', 'Typing speed', 'Reading aloud', 'Grammar rules'], 'answer' => 0],
    ],
    'answer_short' => [
        ['q' => 'Answer Short Question expects:', 'options' => ['A full essay', 'One word or a short phrase', 'A 2-minute speech', 'Silence'], 'answer' => 1],
        ['q' => '"What is the opposite of \'ascend\'?" — Best answer:', 'options' => ['Descend', 'Climb', 'Rise', 'Fly'], 'answer' => 0],
        ['q' => '"What do you call a doctor who treats teeth?"', 'options' => ['Dentist', 'Optician', 'Surgeon', 'Therapist'], 'answer' => 0],
        ['q' => '"What is the first month of the year?"', 'options' => ['December', 'January', 'March', 'June'], 'answer' => 1],
        ['q' => 'Response time expected for this task is:', 'options' => ['Very fast, a few seconds', 'Up to 5 minutes', 'No limit', '1 hour'], 'answer' => 0],
    ],
    'summarize_spoken' => [
        ['q' => 'Summarize Spoken Text should be written in:', 'options' => ['Bullet points only', 'One well-formed paragraph', 'Random words', 'A poem'], 'answer' => 1],
        ['q' => 'Typical word count target is around:', 'options' => ['5–10 words', '50–70 words', '500 words', '1 word'], 'answer' => 1],
        ['q' => 'You should focus on capturing:', 'options' => ['Every single word', 'The main idea and key supporting points', 'Only the introduction', 'Only numbers mentioned'], 'answer' => 1],
        ['q' => 'Which is graded in this task?', 'options' => ['Content, form & grammar', 'Handwriting neatness', 'Font choice', 'Drawing skill'], 'answer' => 0],
        ['q' => 'Best way to prepare while listening:', 'options' => ['Close your eyes and relax', 'Jot down key points as you hear them', 'Hum along', 'Translate word-for-word'], 'answer' => 1],
    ],
    'mcq_single' => [
        ['q' => 'Choose the correct synonym for "abundant":', 'options' => ['Scarce', 'Plentiful', 'Empty', 'Broken'], 'answer' => 1],
        ['q' => 'This task type requires you to pick:', 'options' => ['Exactly one correct option', 'All correct options', 'No options', 'Two random options'], 'answer' => 0],
        ['q' => 'Choose the correct meaning of "meticulous":', 'options' => ['Careless', 'Very careful and precise', 'Loud', 'Quick'], 'answer' => 1],
        ['q' => 'Which strategy helps most here?', 'options' => ['Eliminate clearly wrong options first', 'Pick the longest option', 'Always choose option A', 'Guess without reading'], 'answer' => 0],
        ['q' => 'This question type is usually based on:', 'options' => ['A short recording or passage', 'Nothing at all', 'A blank page', 'Pure guesswork'], 'answer' => 0],
    ],
    'mcq_multiple' => [
        ['q' => 'In this task, the number of correct answers is:', 'options' => ['Always exactly one', 'One or more, unknown in advance', 'Always zero', 'Always all of them'], 'answer' => 1],
        ['q' => 'Selecting an extra wrong option usually:', 'options' => ['Has no effect', 'Loses you marks', 'Gives bonus marks', 'Ends the test'], 'answer' => 1],
        ['q' => 'Best approach is to:', 'options' => ['Read all options carefully before choosing', 'Choose the first option only', 'Pick options with the fewest words', 'Skip reading the passage'], 'answer' => 0],
        ['q' => 'Which is a valid strategy for partial credit tasks?', 'options' => ['Select only options you are confident about', 'Select every option', 'Select nothing', 'Select randomly'], 'answer' => 0],
        ['q' => 'These questions are typically linked to:', 'options' => ['A passage or audio clip', 'Nothing provided', 'Only images', 'Only your opinion'], 'answer' => 0],
    ],
    'fill_blanks' => [
        ['q' => '"She has been working here ___ 2019." Best fit:', 'options' => ['since', 'for', 'from', 'at'], 'answer' => 0],
        ['q' => '"He is interested ___ learning French." Best fit:', 'options' => ['on', 'in', 'at', 'for'], 'answer' => 1],
        ['q' => '"Despite ___ tired, she finished the report." Best fit:', 'options' => ['be', 'being', 'been', 'to be'], 'answer' => 1],
        ['q' => '"The committee ___ meeting tomorrow." Best fit:', 'options' => ['is', 'are', 'be', 'been'], 'answer' => 0],
        ['q' => 'Fill in the Blanks mainly tests:', 'options' => ['Vocabulary & grammar in context', 'Listening speed', 'Drawing ability', 'Memory of images'], 'answer' => 0],
    ],
    'highlight_summary' => [
        ['q' => 'Highlight Correct Summary asks you to:', 'options' => ['Write your own summary', 'Pick the summary that best matches the recording', 'Ignore the audio', 'Translate the audio'], 'answer' => 1],
        ['q' => 'A "close but wrong" summary usually:', 'options' => ['Misses key details or adds false ones', 'Is always correct', 'Is identical to the audio', 'Has no words'], 'answer' => 0],
        ['q' => 'Best strategy for this task:', 'options' => ['Note key points while listening, then compare', 'Guess without listening', 'Choose the shortest option', 'Choose the longest option'], 'answer' => 0],
        ['q' => 'This task combines which two skills?', 'options' => ['Listening & reading comprehension', 'Speaking & writing', 'Drawing & singing', 'Typing & memory'], 'answer' => 0],
        ['q' => 'A correct summary should reflect:', 'options' => ['The overall meaning, not just one detail', 'Only the first sentence', 'Only numbers mentioned', 'A random unrelated topic'], 'answer' => 0],
    ],
];
    }

    public static function sampleNotifications(): array
    {
        return [
    ['title' => 'New Sectional Test unlocked', 'time' => '2h ago', 'unread' => true],
    ['title' => 'Your Repeat Sentence accuracy dropped 8%', 'time' => '5h ago', 'unread' => true],
    ['title' => 'Mira suggests a Focus Practice session', 'time' => '1d ago', 'unread' => true],
    ['title' => 'Streak milestone: 12 days in a row 🔥', 'time' => '1d ago', 'unread' => true],
    ['title' => 'Weekly performance report is ready', 'time' => '2d ago', 'unread' => true],
    ['title' => 'Live Class starting soon', 'time' => '3d ago', 'unread' => true],
];
    }

    /* ------------------------------------------------------------------
     | Session bootstrap — first-visit defaults
     * ------------------------------------------------------------------ */
    public static function boot(): void
    {
        if (session()->has('boot')) {
            return;
        }

        session()->put('boot', true);

        $typeStats = [];
        $defaults  = self::defaultTypeAccuracy();
        foreach (self::questionTypes() as $t) {
            $typeStats[$t['id']] = ['correct' => $defaults[$t['id']], 'total' => 100];
        }
        session()->put('type_stats', $typeStats);

        $macroStats = [];
        foreach (self::macroSkills() as $key => $m) {
            $macroStats[$key] = ['correct' => $m['accuracy'], 'total' => 100];
        }
        session()->put('macro_stats', $macroStats);

        session()->put('recent', [
            ['type' => 'repeat_sentence',  'name' => 'Repeat Sentence',                 'count' => 20, 'accuracy' => 65],
            ['type' => 'describe_image',   'name' => 'Describe Image',                  'count' => 10, 'accuracy' => 80],
            ['type' => 'fill_blanks',      'name' => 'Reading: Fill in the Blanks',     'count' => 15, 'accuracy' => 60],
            ['type' => 'summarize_spoken', 'name' => 'Listening: Summarize Spoken Text','count' => 10, 'accuracy' => 70],
        ]);

        session()->put('weekly',      [12, 22, 14, 38, 20, 6, 2]);
        session()->put('streak_days', [true, true, true, true, true, true, false]);
        session()->put('streak_count', 12);
        session()->put('bookmarks', []);
        session()->put('last_full_score', ['score' => 64, 'total' => 90, 'date' => '20 May 2024']);
        session()->put('last_sectional', ['score' => 72, 'total' => 90, 'attempts' => 12]);
        session()->put('unread_notifications', 6);
        session()->put('trend', [58, 61, 55, 64, 68, 63, 70, 66, 68, 68]);
    }

    public static function reset(): void
    {
        foreach ([
            'boot', 'type_stats', 'macro_stats', 'recent', 'weekly', 'streak_days',
            'streak_count', 'bookmarks', 'last_full_score', 'last_sectional',
            'unread_notifications', 'trend',
        ] as $key) {
            session()->forget($key);
        }
    }

    /* ------------------------------------------------------------------
     | Maths
     * ------------------------------------------------------------------ */
    public static function pct($correct, $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($correct / $total) * 100);
    }

    public static function typeAccuracy(string $id): int
    {
        $s = session('type_stats')[$id] ?? ['correct' => 0, 'total' => 0];

        return self::pct($s['correct'], $s['total']);
    }

    public static function macroAccuracy(string $key): int
    {
        $s = session('macro_stats')[$key] ?? ['correct' => 0, 'total' => 0];

        return self::pct($s['correct'], $s['total']);
    }

    public static function overallAccuracy(): int
    {
        $keys = array_keys(self::macroSkills());
        $sum  = 0;
        foreach ($keys as $k) {
            $sum += self::macroAccuracy($k);
        }

        return (int) round($sum / max(1, count($keys)));
    }

    public static function macroFor(string $typeId): ?string
    {
        foreach (self::questionTypes() as $t) {
            if ($t['id'] === $typeId) {
                return $t['macro'];
            }
        }

        return null;
    }

    public static function labelFor(string $typeId): string
    {
        foreach (self::questionTypes() as $t) {
            if ($t['id'] === $typeId) {
                return $t['name'];
            }
        }

        return '';
    }

    public static function colorVars(string $color): array
    {
        $map = [
            'blue'   => ['#EEF2FF', '#4F6BFF'],
            'green'  => ['#E9FBF3', '#12B76A'],
            'orange' => ['#FFF3E8', '#F7931E'],
            'indigo' => ['#EFEBFF', '#6C5CE0'],
            'rose'   => ['#FFECEF', '#F0446E'],
            'teal'   => ['#E6FBF8', '#0EA5A0'],
            'amber'  => ['#FFF6E0', '#E0A400'],
            'violet' => ['#F1EAFF', '#8B5CF6'],
            'pink'   => ['#FFEAF5', '#EC4899'],
            'cyan'   => ['#E6F9FF', '#0EA5E9'],
        ];

        return $map[$color] ?? ['#EEF2FF', '#4F6BFF'];
    }
}
