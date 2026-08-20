@extends('layouts.app')

@section('title', 'AI Tutor – Mira — schoolar.ai')
@section('page-title', 'AI Tutor – Mira')
@section('page-sub', "Your personal AI tutor. Always here to help you learn better.")

@php
if (! function_exists('mira_tips_html')) {

    /* ---------------- helpers ---------------- */

    function mira_tips_html(array $tips) {
        $out = "<ol class='mm-tips'>";
        foreach ($tips as $t) {
            $out .= "<li><span class='mm-num'></span><span>" . $t . "</span></li>";
        }
        return $out . "</ol>";
    }

    function mira_practice_sentences() {
        return [
            "The library will be closed for renovation until further notice.",
            "Students are required to submit their assignments before the deadline.",
            "The lecture on climate change has been rescheduled to next Tuesday.",
            "Research shows that regular practice improves long-term memory retention.",
            "The university offers a wide range of scholarships for international students.",
            "Global economic trends influence the policies of developing nations.",
            "Effective communication skills are essential for academic success.",
            "The results of the experiment were published in a scientific journal.",
        ];
    }

    /* ---------------- session bootstrap ---------------- */

    function mira_init() {
        if (! session()->has('mira_chat')) {
            session()->put('mira_chat', [
                [
                    'role' => 'user',
                    'html' => "I'm struggling with Repeat Sentence in PTE. Can you give me some tips?",
                    'time' => date('g:i A'),
                ],
                [
                    'role' => 'bot',
                    'html' => "Of course! Repeat Sentence can be tricky, but with the right approach, you can score high. Here are some key tips:"
                            . mira_tips_html([
                                "Listen carefully to the audio and try to understand the meaning.",
                                "Speak clearly and at a natural pace.",
                                "Don't worry about remembering every single word – focus on key words.",
                                "Practice regularly to improve your fluency and pronunciation.",
                              ])
                            . "<p class='mm-follow'>Would you like to try a quick practice now?</p>",
                    'time' => date('g:i A'),
                ],
            ]);
        }

        if (! session()->has('mira_stats')) {
            session()->put('mira_stats', [
                'improvement' => 78,
                'topics'      => ['repeat-sentence' => true],
                'questions'   => 28,
                'streak'      => 12,
                'tasks_done'  => 7,
            ]);
        }

        if (! session()->has('mira_target')) {
            session()->put('mira_target', null);
        }

        if (! session()->has('mira_recent')) {
            session()->put('mira_recent', [
                ['t' => 'Tips for Repeat Sentence',   'w' => 'Today, ' . date('g:i A')],
                ['t' => 'Explain Fill in the Blanks', 'w' => 'Today, 8:15 AM'],
                ['t' => 'Scoring in PTE Speaking',    'w' => 'Yesterday, 7:45 PM'],
                ['t' => 'Improve Fluency',            'w' => 'Yesterday, 6:30 PM'],
            ]);
        }
    }

    /* ---------------- Mira's brain (rule-based) ---------------- */

    function mira_reply($msg) {
        $m = mb_strtolower(trim($msg));

        /* --- a practice sentence is pending: score this message as the attempt --- */
        if (session('mira_target')) {

            if (preg_match('/\b(stop|quit|exit|cancel)\b/', $m)) {
                session()->put('mira_target', null);
                return "No problem, practice stopped. 😊 Ask me anything else, or type <b>practice</b> whenever you want to try again!";
            }

            $target = session('mira_target');
            session()->put('mira_target', null);

            $stats = session('mira_stats');
            $stats['questions']++;
            session()->put('mira_stats', $stats);

            similar_text(mb_strtolower($target), $m, $pct);
            $pct = round($pct);

            if ($pct >= 85)     { $verdict = "Excellent! 🎉 That's a high-scoring response."; }
            elseif ($pct >= 60) { $verdict = "Good effort! 👍 You captured most of the key words."; }
            else                { $verdict = "Keep practicing! 💪 Focus on the key content words first."; }

            return "<b>Your accuracy: {$pct}%</b><br>{$verdict}"
                 . "<div class='mm-compare'><div><span>Target</span>" . e($target) . "</div>"
                 . "<div><span>You said</span>" . e($msg) . "</div></div>"
                 . "<p class='mm-follow'>Type <b>practice</b> for another sentence, or ask me anything!</p>";
        }

        /* --- start practice --- */
        if (preg_match('/\b(practice|practise|quiz|try|test me|another)\b/', $m) && ! preg_match('/mock/', $m)) {
            $list = mira_practice_sentences();
            $s    = $list[array_rand($list)];
            session()->put('mira_target', $s);

            return "Great! 🎧 Here is your <b>Repeat Sentence</b> practice. Read it once, look away, then type it back from memory:"
                 . "<div class='mm-sentence'>“" . e($s) . "”</div>"
                 . "<p class='mm-follow'>Type your answer below and I'll score your accuracy. (Type <b>stop</b> to exit.)</p>";
        }

        /* --- topic rules --- */
        $rules = [
            'repeat sentence|repeat-sentence' => ['repeat-sentence',
                "Repeat Sentence is worth big marks in both <b>Speaking</b> and <b>Listening</b>. Here's how to master it:"
                . mira_tips_html([
                    "Listen for the *meaning*, not individual words — your brain chunks phrases better than words.",
                    "If you forget the middle, keep fluency — say the beginning and end confidently.",
                    "Never leave it blank; content is scored on word matches, so partial answers earn points.",
                    "Shadow native audio daily for 10 minutes to build phonological memory.",
                  ])
                . "<p class='mm-follow'>Would you like to try a quick practice now? Just type <b>practice</b>.</p>"],

            'fill in the blank' => ['fill-blanks',
                "For <b>Fill in the Blanks</b>:"
                . mira_tips_html([
                    "Read the full sentence first — grammar (verb tense, prepositions) eliminates half the options.",
                    "Learn collocations: words that naturally go together (e.g. 'conduct research', 'raise awareness').",
                    "In reading+writing FIB, check the word *before and after* the blank for grammatical fit.",
                  ])],

            'fluen' => ['fluency',
                "Fluency is about smooth, continuous speech — <b>not speed</b>. Try this:"
                . mira_tips_html([
                    "Never self-correct mid-sentence; a small wrong word hurts less than a restart.",
                    "Speak in phrase groups of 3–5 words with tiny pauses between groups.",
                    "Record yourself for 60 seconds daily and count your hesitations — aim to reduce weekly.",
                  ])],

            'pronunc' => ['pronunciation',
                "For <b>Pronunciation</b>, the PTE scorer checks vowels, consonants and word stress:"
                . mira_tips_html([
                    "Focus on word endings — dropped '-s' and '-ed' sounds are the most common point-killers.",
                    "Stress content words (nouns/verbs) and reduce function words (a, of, the).",
                    "Use minimal-pair drills: ship/sheep, live/leave, work/walk.",
                  ])],

            'scor|marks|points' => ['scoring',
                "PTE scoring is fully automated, on a scale of 10–90. Key facts:"
                . mira_tips_html([
                    "Speaking items score Content, Fluency and Pronunciation separately.",
                    "Repeat Sentence & Write From Dictation are the biggest cross-skill contributors.",
                    "There is NO negative marking except in multiple-answer MCQs — always attempt everything.",
                  ])],

            'essay|writing|write' => ['writing',
                "For the <b>Essay</b> task (200–300 words in 20 minutes):"
                . mira_tips_html([
                    "Use a fixed 4-paragraph template: intro → idea 1 → idea 2 → conclusion.",
                    "Spend 2 min planning, 15 writing, 3 proofreading (articles, plurals, spelling).",
                    "One clear idea per paragraph beats three half-developed ones.",
                  ])],

            'read' => ['reading',
                "For <b>Reading</b>:"
                . mira_tips_html([
                    "Reorder Paragraphs: find the standalone topic sentence first, then chain pronouns & linkers.",
                    "MCQs: read the question before the passage and scan for keywords.",
                    "Watch the timer — reading is the section where candidates most often run out of time.",
                  ])],

            'listen|summariz|note' => ['listening',
                "For <b>Listening</b>:"
                . mira_tips_html([
                    "Write From Dictation: type key content words even if you miss small words.",
                    "Summarize Spoken Text: note only nouns & verbs while listening, build sentences after.",
                    "Practice at 1.25× speed — real exam audio will feel slow.",
                  ])],

            'vocab|word' => ['vocabulary',
                "Boost your vocabulary the smart way:"
                . mira_tips_html([
                    "Learn words in collocations, not isolation ('heated debate', 'crucial role').",
                    "10 new academic words daily from the Academic Word List beats 50 random ones.",
                    "Recycle: use each new word in one spoken and one written sentence the same day.",
                  ])
                . "<p class='mm-follow'>Check the <b>Vocabulary</b> tab for today's 10 words! 📚</p>"],

            'mock' => ['mock-test',
                "About <b>Mock Tests</b>:"
                . mira_tips_html([
                    "Take one full mock weekly under strict timed conditions.",
                    "Review errors the SAME day — that's where the real learning happens.",
                    "Your next scheduled mock is due <b>tomorrow</b> — you've got this! 🔥",
                  ])],

            'strateg|tip|template' => ['strategies',
                "My top exam-day strategies:"
                . mira_tips_html([
                    "Answer every question — most items have no negative marking.",
                    "Use templates for Describe Image & Retell Lecture to guarantee fluency.",
                    "Manage energy: Speaking comes first, so warm up your voice before the exam.",
                  ])],

            'hello|^hi\b|hey' => [null,
                "Hi Arjun! 👋 Great to see you keeping that <b>12-day streak</b> alive! Ask me about any PTE topic, or type <b>practice</b> to drill Repeat Sentence."],

            'thank' => [null,
                "You're welcome! 😊 Keep up the great work — consistent practice is what moves that 78% higher. Anything else?"],
        ];

        foreach ($rules as $pattern => $data) {
            if (preg_match('/(' . $pattern . ')/', $m)) {
                if ($data[0]) {
                    $stats = session('mira_stats');
                    $stats['topics'][$data[0]] = true;
                    session()->put('mira_stats', $stats);
                }
                return $data[1];
            }
        }

        /* --- default --- */
        return "That's a great question! I can help you with:"
             . mira_tips_html([
                 "<b>Speaking</b> — fluency, pronunciation, Repeat Sentence, Describe Image",
                 "<b>Writing</b> — essays, grammar, templates",
                 "<b>Reading & Listening</b> — fill in the blanks, MCQs, note taking",
                 "<b>Scoring & strategies</b> — how PTE marks you, exam-day tactics",
               ])
             . "<p class='mm-follow'>Try asking: <i>“How is speaking scored?”</i> or type <b>practice</b> to start a drill!</p>";
    }

    /**
     * Echo JSON and stop.
     *  - session()->save() first: exit skips the middleware that writes the
     *    session, so the chat history would be lost without it.
     *  - ob_clean() drops the whitespace Blade emits before this point.
     */
    function mira_json($payload) {
        session()->save();
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

mira_init();

/* ================= AJAX endpoints (GET with a query string) ================= */

if (request()->filled('action')) {

    $action = request()->input('action');

    if ($action === 'send_message') {
        $text = trim((string) request()->input('message', ''));

        if ($text === '' || mb_strlen($text) > 1000) {
            mira_json(['ok' => false]);
        }

        $chat = session('mira_chat');
        $chat[] = ['role' => 'user', 'html' => e($text), 'time' => date('g:i A')];
        session()->put('mira_chat', $chat);

        $reply = mira_reply($text);

        $chat = session('mira_chat');
        $chat[] = ['role' => 'bot', 'html' => $reply, 'time' => date('g:i A')];
        session()->put('mira_chat', $chat);

        $s = session('mira_stats');

        mira_json([
            'ok'    => true,
            'reply' => $reply,
            'time'  => date('g:i A'),
            'stats' => [
                'topics'      => count($s['topics']),
                'questions'   => $s['questions'],
                'improvement' => $s['improvement'],
            ],
        ]);
    }

    if ($action === 'reset_chat') {
        session()->forget(['mira_chat', 'mira_target']);
        mira_init();
        mira_json(['ok' => true]);
    }

    mira_json(['ok' => false]);
}

/* ================= data for the page ================= */

$stats  = session('mira_stats');
$chat   = session('mira_chat');
$recent = session('mira_recent');
@endphp

@php($active = 'mira')

@push('styles')
<style>
:root{
  --ink:#0f0f2d; --sidebar:#12102e; --sidebar2:#1a1740;
  --violet:#6d3ef2; --violet2:#8b5cf6; --violet-soft:#efeafe;
  --bg:#f4f4fb; --card:#ffffff; --line:#ebeaf5;
  --txt:#23233f; --mut:#8a89a3; --green:#22c55e; --orange:#f97316;
}
.page-content *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',system-ui,-apple-system,sans-serif}
.page-content .logo{display:flex;align-items:center;gap:10px;padding:22px 20px 18px;color:#fff;font-size:19px;font-weight:700}
.page-content .logo .mark{width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#7c3aed,#22d3ee);display:grid;place-items:center;font-size:17px;color:#fff}
.page-content .badge-live{margin-left:auto;background:var(--violet);color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px}
.page-content .profile{display:flex;align-items:center;gap:10px;padding:14px 20px;border-top:1px solid #262254}
.page-content .avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#ef4444);display:grid;place-items:center;color:#fff;font-weight:700;font-size:14px}
.page-content .profile b{color:#fff;font-size:13px;display:block}
.page-content .profile span{font-size:11px;color:#8f8db2}
.page-content .beta{background:var(--violet-soft);color:var(--violet);font-size:11px;font-weight:700;padding:3px 9px;border-radius:14px;vertical-align:middle}
.page-content .subtitle{font-size:13px;color:var(--mut)}
.page-content .pill{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:9px 14px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;cursor:pointer}
.page-content .bell{position:relative}
.page-content .bell .dot{position:absolute;top:-6px;right:-7px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;border-radius:10px;padding:1px 5px}
.page-content .streak-pill{color:#e2590a}
.page-content .columns{display:grid;grid-template-columns:215px minmax(0,1fr) 300px;gap:16px;padding:4px 26px 26px;align-items:start}
.page-content .card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:16px}
.page-content .card h3{font-size:14.5px;color:var(--ink);margin-bottom:12px}
.page-content .help-item{display:flex;gap:11px;padding:9px 6px;border-radius:10px;cursor:pointer;transition:.15s}
.page-content .help-item:hover{background:var(--violet-soft)}
.page-content .help-item .hico{width:34px;height:34px;border-radius:9px;display:grid;place-items:center;font-size:15px;flex-shrink:0}
.page-content .help-item b{font-size:12.5px;color:var(--ink);display:block}
.page-content .help-item span{font-size:11px;color:var(--mut)}
.page-content .focus-row{display:flex;justify-content:space-between;align-items:center;gap:8px}
.page-content .tag{background:#fef3e6;color:#ea7317;font-size:10px;font-weight:700;padding:3px 8px;border-radius:12px;display:inline-block;margin-top:5px}
.page-content .ring{--p:70;width:62px;height:62px;border-radius:50%;background:conic-gradient(var(--violet) calc(var(--p)*1%),#eceafe 0);display:grid;place-items:center;flex-shrink:0}
.page-content .ring i{width:48px;height:48px;background:#fff;border-radius:50%;display:grid;place-items:center;font-style:normal;font-weight:700;font-size:13px;color:var(--ink)}
.page-content .pbar{height:7px;background:#eceafe;border-radius:6px;margin:12px 0 6px;overflow:hidden}
.page-content .pbar div{height:100%;width:70%;background:linear-gradient(90deg,#6d3ef2,#9f6bff);border-radius:6px}
.page-content .small{font-size:11.5px;color:var(--mut)}
.page-content .btn-line{width:100%;margin-top:12px;background:var(--violet-soft);color:var(--violet);border:0;padding:9px;border-radius:9px;font-weight:600;font-size:12.5px;cursor:pointer}
.page-content .btn-line:hover{background:#e3d9fd}
.page-content .week{display:flex;gap:6px;margin-top:10px}
.page-content .week i{width:22px;height:22px;border-radius:50%;background:var(--green);color:#fff;display:grid;place-items:center;font-size:11px;font-style:normal}
.page-content .flame{font-size:15px}
.page-content .chat-card{display:flex;flex-direction:column;min-height:640px;padding:0;overflow:hidden}
.page-content .chat-scroll{flex:1;overflow-y:auto;padding:22px 22px 8px;display:flex;flex-direction:column;gap:14px}
.page-content .mira-head{display:flex;gap:13px;align-items:flex-start}
.page-content .mira-ava{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#e9e4ff,#f6f3ff);display:grid;place-items:center;font-size:22px;flex-shrink:0;border:2px solid #e6defc}
.page-content .mira-head h2{font-size:18px;color:var(--ink)}
.page-content .mira-head p{font-size:13.5px;color:#5d5c78;margin-top:3px}
.page-content .chips{display:flex;flex-wrap:wrap;gap:8px;margin:4px 0 2px 57px}
.page-content .chip{background:#fff;border:1px solid var(--line);border-radius:10px;padding:8px 13px;font-size:12.5px;font-weight:600;color:#41405e;cursor:pointer;display:flex;gap:7px;align-items:center;transition:.15s}
.page-content .chip:hover{border-color:var(--violet2);color:var(--violet);background:var(--violet-soft)}
.page-content .msg{display:flex;gap:10px;max-width:88%}
.page-content .msg.user{align-self:flex-end;flex-direction:row-reverse}
.page-content .msg .bub{padding:13px 16px;border-radius:14px;font-size:13.5px;line-height:1.55}
.page-content .msg.user .bub{background:var(--violet-soft);border:1px solid #e2d7fd;color:#33325a;border-bottom-right-radius:4px}
.page-content .msg.bot .bub{background:#fff;border:1px solid var(--line);box-shadow:0 4px 14px rgba(30,27,75,.05);border-top-left-radius:4px;color:#33325a}
.page-content .msg .meta{font-size:11px;color:var(--mut);margin-top:5px;text-align:right}
.page-content .msg.bot .meta{text-align:left}
.page-content .msg .mava{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#e9e4ff,#f6f3ff);border:2px solid #e6defc;display:grid;place-items:center;font-size:16px;flex-shrink:0;margin-top:2px}
.page-content .mm-tips{list-style:none;margin:10px 0 2px;display:flex;flex-direction:column;gap:8px;counter-reset:tip}
.page-content .mm-tips li{display:flex;gap:9px;align-items:flex-start}
.page-content .mm-tips .mm-num{counter-increment:tip;width:20px;height:20px;border-radius:50%;background:var(--violet);color:#fff;font-size:11px;font-weight:700;display:grid;place-items:center;flex-shrink:0;margin-top:1px}
.page-content .mm-tips .mm-num::before{content:counter(tip)}
.page-content .mm-follow{margin-top:10px;font-weight:600;color:var(--ink)}
.page-content .mm-sentence{background:var(--violet-soft);border:1px dashed #c9b4fb;border-radius:10px;padding:11px 14px;margin:10px 0;font-weight:600;color:#4c1d95}
.page-content .mm-compare{display:flex;flex-direction:column;gap:6px;margin-top:9px;font-size:12.5px}
.page-content .mm-compare>div{background:#f8f7fe;border:1px solid var(--line);border-radius:8px;padding:7px 10px}
.page-content .mm-compare span{display:block;font-size:10px;font-weight:700;color:var(--mut);text-transform:uppercase;letter-spacing:.4px}
.page-content .typing{display:inline-flex;gap:4px;padding:4px 2px}
.page-content .typing i{width:7px;height:7px;border-radius:50%;background:#b7a6f0;animation:blink 1.2s infinite}
.page-content .typing i:nth-child(2){animation-delay:.2s}
.page-content .typing i:nth-child(3){animation-delay:.4s}
@keyframes blink{0%,80%,100%{opacity:.25}40%{opacity:1}}
.page-content .action-row{display:flex;flex-wrap:wrap;gap:9px;padding:4px 22px 10px}
.page-content .btn-primary{background:var(--violet);color:#fff;border:0;border-radius:10px;padding:10px 16px;font-weight:600;font-size:13px;cursor:pointer;display:flex;gap:7px;align-items:center}
.page-content .btn-primary:hover{background:#5b2fe0}
.page-content .btn-ghost{background:#fff;border:1px solid var(--line);border-radius:10px;padding:10px 15px;font-weight:600;font-size:13px;color:#41405e;cursor:pointer;display:flex;gap:7px;align-items:center}
.page-content .btn-ghost:hover{border-color:var(--violet2);color:var(--violet)}
.page-content .reco{padding:8px 22px 16px;border-top:1px solid var(--line)}
.page-content .reco-head{display:flex;justify-content:space-between;align-items:center;margin:10px 0}
.page-content .reco-head h3{font-size:14px}
.page-content .reco-head a{font-size:12px;color:var(--violet);text-decoration:none;font-weight:600;cursor:pointer}
.page-content .reco-track{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.page-content .rcard{border:1px solid var(--line);border-radius:12px;padding:12px;font-size:12px;cursor:pointer;transition:.15s}
.page-content .rcard:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(30,27,75,.08)}
.page-content .rcard .rtag{font-size:10px;font-weight:700;display:flex;gap:5px;align-items:center;margin-bottom:7px}
.page-content .rcard b{display:block;color:var(--ink);font-size:12.5px;line-height:1.35;margin-bottom:6px}
.page-content .rcard .small{display:flex;justify-content:space-between;align-items:center}
.page-content .rc-green{background:#f1fdf5}
.page-content .rc-green .rtag{color:#16a34a}
.page-content .rc-violet{background:#f7f4ff}
.page-content .rc-violet .rtag{color:var(--violet)}
.page-content .rc-amber{background:#fffaf0}
.page-content .rc-amber .rtag{color:#d97706}
.page-content .rc-blue{background:#f3f8ff}
.page-content .rc-blue .rtag{color:#2563eb}
.page-content .start-mini{background:var(--green);color:#fff;border:0;border-radius:7px;padding:4px 12px;font-size:11px;font-weight:700;cursor:pointer}
.page-content .input-bar{border-top:1px solid var(--line);padding:14px 22px 8px}
.page-content .input-shell{display:flex;align-items:center;gap:10px;background:#fff;border:1.5px solid var(--line);border-radius:14px;padding:8px 10px 8px 16px;transition:.15s}
.page-content .input-shell:focus-within{border-color:var(--violet2);box-shadow:0 0 0 4px rgba(139,92,246,.12)}
.page-content .input-shell input{flex:1;border:0;outline:0;font-size:14px;color:var(--ink);background:transparent}
.page-content .in-btn{background:none;border:0;color:#6b6a8a;font-size:13px;cursor:pointer;display:flex;gap:6px;align-items:center;padding:6px 8px;border-radius:8px}
.page-content .in-btn:hover{background:var(--violet-soft);color:var(--violet)}
.page-content .send{width:40px;height:40px;border-radius:11px;background:var(--violet);border:0;color:#fff;font-size:16px;cursor:pointer;flex-shrink:0}
.page-content .send:hover{background:#5b2fe0}
.page-content .disclaimer{text-align:center;font-size:11px;color:#a2a1bb;padding:8px 0 12px}
.page-content .right-col{display:flex;flex-direction:column;gap:16px}
.page-content .panel-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.page-content .panel-head h3{margin:0}
.page-content .panel-head a{font-size:12px;color:var(--violet);font-weight:600;text-decoration:none;cursor:pointer}
.page-content .insight-top{display:flex;gap:14px;align-items:center}
.page-content .ring-lg{--p:78;width:84px;height:84px;border-radius:50%;background:conic-gradient(var(--violet) calc(var(--p)*1%),#eceafe 0);display:grid;place-items:center;flex-shrink:0}
.page-content .ring-lg i{width:66px;height:66px;background:#fff;border-radius:50%;display:grid;place-items:center;font-style:normal;font-weight:800;font-size:17px;color:var(--ink)}
.page-content .insight-top b{font-size:14px;color:var(--ink);display:block}
.page-content .up{color:var(--green);font-size:12px;font-weight:700}
.page-content .stat-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-top:16px;text-align:center}
.page-content .stat-row .st b{font-size:17px;color:var(--ink);display:block}
.page-content .stat-row .st span{font-size:10.5px;color:var(--mut);line-height:1.3;display:block;margin-top:2px}
.page-content .stat-row .st i{font-style:normal;font-size:14px}
.page-content .reco-item{display:flex;gap:11px;align-items:center;padding:10px 6px;border-radius:10px;cursor:pointer}
.page-content .reco-item:hover{background:var(--violet-soft)}
.page-content .reco-item .hico{width:36px;height:36px;border-radius:9px;display:grid;place-items:center;font-size:15px;flex-shrink:0}
.page-content .reco-item b{font-size:12.5px;color:var(--ink);display:block}
.page-content .reco-item span{font-size:11px;color:var(--mut)}
.page-content .reco-item .arr{margin-left:auto;color:#b9b8d0}
.page-content .conv{display:flex;gap:10px;align-items:center;padding:9px 6px;border-radius:10px;cursor:pointer}
.page-content .conv:hover{background:var(--violet-soft)}
.page-content .conv .cico{width:30px;height:30px;border-radius:8px;background:var(--violet-soft);color:var(--violet);display:grid;place-items:center;font-size:13px;flex-shrink:0}
.page-content .conv b{font-size:12.5px;color:var(--ink);display:block;font-weight:600}
.page-content .conv span{font-size:11px;color:var(--mut)}
.page-content .conv .dots{margin-left:auto;color:#b9b8d0;cursor:pointer;padding:4px}
@media(max-width:1200px){
.page-content .columns{grid-template-columns:1fr}
.page-content .left-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
.page-content .right-col{display:grid;grid-template-columns:1fr 1fr;gap:16px}
}
@media(max-width:800px){
.page-content .left-col, .page-content .right-col{grid-template-columns:1fr}
.page-content .reco-track{grid-template-columns:1fr 1fr}
}
</style>
@endpush

@section('content')
<div class="columns">

    {{-- ===== LEFT COLUMN ===== --}}
    <div class="left-col" style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <h3>Mira can help you with</h3>
        <div class="help-item" onclick="askMira('How can I improve my speaking fluency and pronunciation?')">
          <div class="hico" style="background:#f1e9ff;color:#7c3aed">🎤</div>
          <div><b>Speaking</b><span>Fluency, pronunciation, CDI</span></div>
        </div>
        <div class="help-item" onclick="askMira('Give me tips for the listening section and note taking')">
          <div class="hico" style="background:#e5f2ff;color:#2563eb">🎧</div>
          <div><b>Listening</b><span>Summarize, note taking</span></div>
        </div>
        <div class="help-item" onclick="askMira('How do I get better at reading fill in the blanks and MCQs?')">
          <div class="hico" style="background:#e6faf0;color:#16a34a">📖</div>
          <div><b>Reading</b><span>Fill in the blanks, MCQs</span></div>
        </div>
        <div class="help-item" onclick="askMira('Help me with essay writing templates and grammar')">
          <div class="hico" style="background:#fff1e8;color:#ea580c">✍️</div>
          <div><b>Writing</b><span>Essay, grammar, templates</span></div>
        </div>
        <div class="help-item" onclick="askMira('How can I build my vocabulary and word usage?')">
          <div class="hico" style="background:#fdf0e2;color:#d97706">📚</div>
          <div><b>Vocabulary</b><span>Word list, usage, practice</span></div>
        </div>
        <div class="help-item" onclick="askMira('What are the best exam strategies and templates?')">
          <div class="hico" style="background:#f1e9ff;color:#7c3aed">🧠</div>
          <div><b>Exam Strategies</b><span>Tips, templates, techniques</span></div>
        </div>
      </div>

      <div class="card">
        <h3>Today's Focus</h3>
        <div class="focus-row">
          <div><b style="font-size:13.5px;color:var(--ink)">Repeat Sentence</b><br><span class="tag">High Impact</span></div>
          <div class="ring"><i>70%</i></div>
        </div>
        <div class="pbar"><div></div></div>
        <div class="small">{{ (int) $stats['tasks_done'] }} / 10 tasks completed</div>
        <button class="btn-line" onclick="askMira('practice')">Continue Plan →</button>
      </div>

      <div class="card">
        <h3>Study Streak</h3>
        <div style="display:flex;align-items:center;gap:8px">
          <span class="flame">🔥</span>
          <b style="font-size:19px;color:var(--ink)">{{ (int) $stats['streak'] }}</b>
          <span class="small">Days in a row</span>
        </div>
        <div class="week">
          <i>✓</i><i>✓</i><i>✓</i><i>✓</i><i>✓</i><i>✓</i><i style="background:#eceafe;color:#8b5cf6">✓</i>
        </div>
        <div class="small" style="margin-top:7px;display:flex;gap:13px">
          <span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span><span>S</span>
        </div>
      </div>
    </div>

    {{-- ===== CHAT COLUMN ===== --}}
    <div class="card chat-card">
      <div class="chat-scroll" id="chatScroll">
        <div class="mira-head">
          <div class="mira-ava">🤖</div>
          <div>
            <h2>Hi Arjun! 👋</h2>
            <p>I'm Mira. How can I help you today?</p>
          </div>
        </div>
        <div class="chips">
          <button class="chip" onclick="askMira('Can you explain a concept to me?')">🗂 Explain a concept</button>
          <button class="chip" onclick="askMira('Can you check my answer?')">✅ Check my answer</button>
          <button class="chip" onclick="askMira('How do I improve my score?')">📈 Improve my score</button>
          <button class="chip" onclick="askMira('What study strategies should I use?')">🎓 Study strategies</button>
        </div>

        @foreach ($chat as $m)
          @if ($m['role'] === 'user')
            <div class="msg user">
              <div>
                <div class="bub">{!! $m['html'] !!}</div>
                <div class="meta">You · {{ $m['time'] }} ✓✓</div>
              </div>
            </div>
          @else
            <div class="msg bot">
              <div class="mava">🤖</div>
              <div>
                <div class="meta">Mira · {{ $m['time'] }}</div>
                <div class="bub">{!! $m['html'] !!}</div>
              </div>
            </div>
          @endif
        @endforeach
      </div>

      <div class="action-row">
        <button class="btn-primary" onclick="askMira('practice')">▶ Practice 10 Questions</button>
        <button class="btn-ghost" onclick="askMira('Show me an example of repeat sentence')">👁 Show me an example</button>
        <button class="btn-ghost" onclick="askMira('Give me more tips')">💡 More tips</button>
        <button class="btn-ghost" onclick="askMira('Explain how scoring works')">📊 Explain scoring</button>
        <button class="btn-ghost" onclick="resetChat()" title="Clear conversation">🗑 Reset</button>
      </div>

      <div class="reco">
        <div class="reco-head"><h3>Recommended for you</h3><a onclick="askMira('What should I practice next?')">View All</a></div>
        <div class="reco-track">
          <div class="rcard rc-green" onclick="askMira('practice')">
            <div class="rtag">📋 Practice Set</div><b>Repeat Sentence<br>10 Questions</b>
            <div class="small"><span>⏱ 15 min</span><button class="start-mini">Start</button></div>
          </div>
          <div class="rcard rc-violet" onclick="askMira('How to improve fluency?')">
            <div class="rtag">▶ Video Lesson</div><b>How to Improve Fluency</b>
            <div class="small"><span>6 min</span><span>▶</span></div>
          </div>
          <div class="rcard rc-amber" onclick="askMira('What are common mistakes in repeat sentence?')">
            <div class="rtag">📙 Study Guide</div><b>Common Mistakes in Repeat Sentence</b>
            <div class="small"><span>4 min read</span><span>📄</span></div>
          </div>
          <div class="rcard rc-blue" onclick="askMira('Show me high scoring sample answers')">
            <div class="rtag">📄 Samples</div><b>High Scoring Sample Answers</b>
            <div class="small"><span>8+ Samples</span></div>
          </div>
        </div>
      </div>

      <div class="input-bar">
        <form class="input-shell" id="chatForm" onsubmit="return sendFromInput(event)">
          <button type="button" class="in-btn" title="Attach">＋</button>
          <input type="text" id="chatInput" placeholder="Type your question or request..." autocomplete="off" maxlength="1000">
          <button type="button" class="in-btn">⬆ Upload</button>
          <button type="button" class="in-btn">🎙 Voice Input</button>
          <button type="submit" class="send">➤</button>
        </form>
      </div>
      <div class="disclaimer">Mira can make mistakes. Please double-check important information.</div>
    </div>

    {{-- ===== RIGHT COLUMN ===== --}}
    <div class="right-col">
      <div class="card">
        <div class="panel-head"><h3>Mira Insights</h3><a>View All</a></div>
        <div class="insight-top">
          <div class="ring-lg" id="impRing" style="--p:{{ (int) $stats['improvement'] }}">
            <i id="impPct">{{ (int) $stats['improvement'] }}%</i>
          </div>
          <div><b>Overall Improvement</b><span class="up">↑ 12%</span> <span class="small">this week</span></div>
        </div>
        <div class="stat-row">
          <div class="st"><i>📌</i><b id="statTopics">{{ count($stats['topics']) }}</b><span>Topics Covered</span></div>
          <div class="st"><i>💬</i><b id="statQuestions">{{ (int) $stats['questions'] }}</b><span>Questions Practiced</span></div>
          <div class="st"><i>⭐</i><b>4.8★</b><span>Satisfaction Score</span></div>
        </div>
      </div>

      <div class="card">
        <div class="panel-head"><h3>Recommended for You</h3></div>
        <div class="reco-item" onclick="askMira('practice')">
          <div class="hico" style="background:#e6faf0;color:#16a34a">💬</div>
          <div><b>Practice Repeat Sentence</b><span>Based on your weak performance</span></div><span class="arr">›</span>
        </div>
        <div class="reco-item" onclick="askMira('How do I improve my pronunciation?')">
          <div class="hico" style="background:#e5f2ff;color:#2563eb">🎧</div>
          <div><b>Improve Pronunciation</b><span>Personalized speaking practice</span></div><span class="arr">›</span>
        </div>
        <div class="reco-item" onclick="askMira('Give me my daily vocabulary boost')">
          <div class="hico" style="background:#fdf0e2;color:#d97706">📚</div>
          <div><b>Daily Vocabulary Boost</b><span>10 new words for you</span></div><span class="arr">›</span>
        </div>
        <div class="reco-item" onclick="askMira('Tell me about my next mock test')">
          <div class="hico" style="background:#f1e9ff;color:#7c3aed">📝</div>
          <div><b>Mock Test Suggestion</b><span>Your next mock is due tomorrow</span></div><span class="arr">›</span>
        </div>
      </div>

      <div class="card">
        <div class="panel-head"><h3>Recent Conversations</h3><a>View All</a></div>
        @foreach ($recent as $c)
          <div class="conv" onclick="askMira({{ Illuminate\Support\Js::from($c['t']) }})">
            <div class="cico">💬</div>
            <div><b>{{ $c['t'] }}</b><span>{{ $c['w'] }}</span></div>
            <span class="dots">⋮</span>
          </div>
        @endforeach
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
/* ---- value injected by Blade (kept outside @verbatim) ---- */
const BASE_URL = @json(url()->current());


const chatScroll = document.getElementById('chatScroll');
const chatInput  = document.getElementById('chatInput');
let busy = false;

function scrollDown(){ chatScroll.scrollTop = chatScroll.scrollHeight; }
scrollDown();

function now(){
  return new Date().toLocaleTimeString('en-US', {hour:'numeric', minute:'2-digit'});
}

/* Every call is a GET with a query string, so this page works on a
   GET-only route (Route::view / Route::get). _t busts any cache. */
let apiSeq = 0;
async function api(params){
  const qs = new URLSearchParams();
  for (const k in params) qs.append(k, params[k]);
  qs.append('_t', Date.now() + '-' + (++apiSeq));
  const r = await fetch(BASE_URL + '?' + qs.toString(), {
    method     : 'GET',
    headers    : {'X-Requested-With': 'XMLHttpRequest'},
    credentials: 'same-origin',
    cache      : 'no-store'
  });
  return r.json();
}

function addUserMsg(text){
  const d = document.createElement('div');
  d.className = 'msg user';
  d.innerHTML = `<div><div class="bub"></div><div class="meta">You · ${now()} ✓✓</div></div>`;
  d.querySelector('.bub').textContent = text;   // textContent, so user input can't inject HTML
  chatScroll.appendChild(d);
  scrollDown();
}

function addTyping(){
  const d = document.createElement('div');
  d.className = 'msg bot';
  d.id = 'typingMsg';
  d.innerHTML = `<div class="mava">🤖</div><div><div class="bub"><span class="typing"><i></i><i></i><i></i></span></div></div>`;
  chatScroll.appendChild(d);
  scrollDown();
}

function addBotMsg(html, time){
  document.getElementById('typingMsg')?.remove();
  const d = document.createElement('div');
  d.className = 'msg bot';
  d.innerHTML = `<div class="mava">🤖</div><div><div class="meta">Mira · ${time}</div><div class="bub">${html}</div></div>`;
  chatScroll.appendChild(d);
  scrollDown();
}

async function askMira(text){
  if (busy || !text.trim()) return;
  busy = true;
  addUserMsg(text);
  addTyping();
  try {
    const data = await api({action:'send_message', message:text});
    await new Promise(r => setTimeout(r, 500));   // let the typing dots breathe
    if (data.ok){
      addBotMsg(data.reply, data.time);
      updateStats(data.stats);
    } else {
      addBotMsg("Sorry, I couldn't process that. Please try again!", now());
    }
  } catch (e){
    document.getElementById('typingMsg')?.remove();
    addBotMsg("⚠️ Connection error — is the server running?", now());
  }
  busy = false;
  chatInput.focus();
}

function sendFromInput(ev){
  ev.preventDefault();
  const t = chatInput.value.trim();
  if (t){ chatInput.value = ''; askMira(t); }
  return false;
}

function updateStats(s){
  if (!s) return;
  document.getElementById('statTopics').textContent    = s.topics;
  document.getElementById('statQuestions').textContent = s.questions;
  document.getElementById('impPct').textContent        = s.improvement + '%';
  document.getElementById('impRing').style.setProperty('--p', s.improvement);
}

async function resetChat(){
  if (busy) return;
  await api({action:'reset_chat'});
  window.location.href = BASE_URL;
}
</script>
@endpush