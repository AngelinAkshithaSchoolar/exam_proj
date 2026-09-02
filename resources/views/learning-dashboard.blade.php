@extends('layouts.app')

{{--
|--------------------------------------------------------------------------
| Learning Dashboard  (K-12 + Exam Prep)
|--------------------------------------------------------------------------
| Extends the shared shell, so the sidebar and topbar are byte-for-byte the
| ones in layouts/app.blade.php — this page adds nothing to them and removes
| nothing from them. It only:
|
|   * fills the topbar-extra slot with the "My Learning" space switcher and
|     the search box (exactly where they sit in the design), and
|   * renders its own body inside the normal content well.
|
| EVERY selector below is prefixed with .ldash and every design token is
| declared ON .ldash rather than on :root. That is deliberate: the shell owns
| --bg, --card, --muted, --shadow and friends, and a bare :root block here
| would silently repaint the sidebar and topbar. Scoping keeps the two
| stylesheets from ever touching each other.
|
| Data: every variable has a ?? fallback, so the page renders standalone.
| Pass any of them from a controller later and the fallback stops applying:
|
|     return view('learning-dashboard', compact('user','tasks','classes'));
|
| Blade gotcha respected throughout: only the BLOCK form of the php directive
| is used, never the inline parenthesised form next to a block, and no Blade
| comment here spells a directive name with its leading at-sign — raw-block
| extraction runs before comments are stripped and would eat the rest of the
| file.
|--------------------------------------------------------------------------
--}}

@php
    /* ─────────────────────────── Who ─────────────────────────── */
    $user = $user ?? [
        'name'     => 'Arjun',
        'greeting' => 'Good Morning',
    ];

    /* ───────────────────── Learning spaces ───────────────────── */
    $spaces = $spaces ?? [
        ['id' => 'all',  'name' => 'My Learning',    'sub' => 'Everything in one place', 'icon' => 'grid'],
        ['id' => 'cbse', 'name' => 'Class 10 · CBSE', 'sub' => 'K-12',                   'icon' => 'book'],
        ['id' => 'neet', 'name' => 'NEET 2027',       'sub' => 'Exam Preparation',       'icon' => 'target'],
    ];

    /* ──────────────────── Learning overview ──────────────────── */
    $overview = $overview ?? [
        [
            'space' => 'cbse',
            'title' => 'Class 10 · CBSE',
            'icon'  => 'book',
            'label' => 'Academic Performance',
            'value' => 85, 'unit' => '%', 'tone' => 'teal',
            'delta' => 8,  'deltaNote' => 'from last week',
            'footValue' => 'Top 10%', 'footNote' => 'in Class',
            'spark' => [52, 58, 55, 64, 61, 70, 68, 76, 74, 82, 85],
        ],
        [
            'space' => 'neet',
            'title' => 'NEET 2027',
            'icon'  => 'target',
            'label' => 'Exam Readiness',
            'value' => 72, 'unit' => 'nd', 'tone' => 'violet',
            'delta' => 5,  'deltaNote' => 'from last week',
            'footValue' => 'Percentile', 'footNote' => 'All India',
            'spark' => [44, 48, 46, 53, 51, 58, 57, 63, 66, 70, 72],
        ],
    ];

    /* ───────────────────────── Today's plan ──────────────────── */
    /* tag: school | neet  — drives the pill colour and the tile icon */
    $tasks = $tasks ?? [
        ['id' => 't1', 'title' => 'Mathematics Assignment', 'sub' => 'Linear Equations',       'tag' => 'school', 'space' => 'cbse', 'icon' => 'book',  'mins' => 30, 'done' => true],
        ['id' => 't2', 'title' => 'Physics Revision',       'sub' => 'Mechanics',              'tag' => 'neet',   'space' => 'neet', 'icon' => 'atom',  'mins' => 45, 'done' => false],
        ['id' => 't3', 'title' => 'Science Revision',       'sub' => 'Chemical Reactions',     'tag' => 'school', 'space' => 'cbse', 'icon' => 'flask', 'mins' => 25, 'done' => true],
        ['id' => 't4', 'title' => 'English Homework',       'sub' => 'Essay Writing',          'tag' => 'school', 'space' => 'cbse', 'icon' => 'pen',   'mins' => 20, 'done' => false],
        ['id' => 't5', 'title' => 'Biology Practice',       'sub' => 'Genetics · 40 Questions','tag' => 'neet',   'space' => 'neet', 'icon' => 'leaf',  'mins' => 45, 'done' => true],
        ['id' => 't6', 'title' => 'Biology Sectional Test', 'sub' => 'Botany · 45 Questions',  'tag' => 'neet',   'space' => 'neet', 'icon' => 'doc',   'mins' => 45, 'done' => false],
    ];

    /* Totals are COMPUTED, never hardcoded, so the header can never disagree
       with the list underneath it. */
    $taskCount = count($tasks);
    $taskMins  = array_sum(array_column($tasks, 'mins'));
    $taskTime  = intdiv($taskMins, 60) . 'h ' . str_pad($taskMins % 60, 2, '0', STR_PAD_LEFT) . 'm';
    $doneCount = count(array_filter($tasks, fn ($t) => $t['done']));

    /* ─────────────────────── Continue learning ───────────────── */
    $continue = $continue ?? [
        [
            'space' => 'cbse', 'title' => 'Class 10 · CBSE', 'icon' => 'book', 'total' => 4, 'tone' => 'teal',
            'items' => [
                ['name' => 'Linear Equations',    'sub' => 'Mathematics', 'pct' => 65, 'icon' => 'book'],
                ['name' => 'Chemical Reactions',  'sub' => 'Science',     'pct' => 40, 'icon' => 'flask'],
                ['name' => 'First Flight – Prose','sub' => 'English',     'pct' => 72, 'icon' => 'pen'],
            ],
        ],
        [
            'space' => 'neet', 'title' => 'NEET 2027', 'icon' => 'target', 'total' => 4, 'tone' => 'violet',
            'items' => [
                ['name' => 'Genetics',          'sub' => 'Biology',   'pct' => 72, 'icon' => 'leaf'],
                ['name' => 'Mechanics',         'sub' => 'Physics',   'pct' => 48, 'icon' => 'atom'],
                ['name' => 'Organic Chemistry', 'sub' => 'Chemistry', 'pct' => 61, 'icon' => 'flask'],
            ],
        ],
    ];

    /* ──────────────────────── My progress ────────────────────── */
    $progress = $progress ?? [
        'week' => [
            'labels' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            'school' => [62, 70, 66, 78, 74, 86, 80],
            'neet'   => [48, 55, 52, 60, 58, 70, 72],
            'stats'  => [
                ['label' => 'Tasks Completed', 'value' => '18',  'note' => '/24'],
                ['label' => 'Study Time',      'value' => '12h', 'note' => '30m'],
                ['label' => 'Accuracy',        'value' => '82%', 'note' => ''],
                ['label' => 'Streak',          'value' => '12',  'note' => 'Days'],
            ],
        ],
        'month' => [
            'labels' => ['Week 1','Week 2','Week 3','Week 4'],
            'school' => [58, 69, 75, 84],
            'neet'   => [41, 50, 63, 71],
            'stats'  => [
                ['label' => 'Tasks Completed', 'value' => '74',  'note' => '/96'],
                ['label' => 'Study Time',      'value' => '51h', 'note' => '10m'],
                ['label' => 'Accuracy',        'value' => '79%', 'note' => ''],
                ['label' => 'Streak',          'value' => '12',  'note' => 'Days'],
            ],
        ],
    ];

    /* ─────────────────────── Right rail data ─────────────────── */
    $classes = $classes ?? [
        ['subject' => 'Mathematics', 'topic' => 'Linear Equations', 'teacher' => 'Mr. Rahul Verma', 'when' => 'Today, 9:00 AM – 10:00 AM', 'tone' => 'teal',   'live' => true],
        ['subject' => 'Physics',     'topic' => 'Mechanics',        'teacher' => 'Ms. Priya Nair',  'when' => 'Today, 6:00 PM – 7:00 PM',  'tone' => 'violet', 'live' => true],
    ];

    $achievements = $achievements ?? [
        ['icon' => '🔥', 'value' => '12',      'label' => 'Day Streak', 'tone' => 'orange'],
        ['icon' => '🏆', 'value' => 'Top 10%', 'label' => 'Class Rank', 'tone' => 'amber'],
        ['icon' => '🛡️', 'value' => 'Scholar', 'label' => 'May 2026',   'tone' => 'rose'],
    ];

    $announcements = $announcements ?? [
        ['title' => 'Summer Scholarship Test', 'line1' => 'For Class 8 – 12', 'line2' => 'Win up to 100% Scholarship', 'cta' => 'Register Now'],
    ];

    $recommendation = $recommendation ?? [
        'body' => 'You have a Chemistry test in school on Friday and your NEET Chemistry is at 61%. Shall we build one study plan that covers both?',
        'cta'  => 'Create Smart Plan',
    ];

    /* AI tutor: chip label => canned reply */
    $tutorChips = $tutorChips ?? [
        'Explain a Concept' => 'Which one? You have three open right now — Linear Equations, Mechanics and Genetics. Name a topic and I will break it into three steps.',
        'Solve a Doubt'     => 'Paste the question or tell me where you got stuck. I will walk you through the reasoning rather than hand you the answer.',
        'Quiz Me'           => 'Quick one. In y = mx + c, what does m represent?  (a) the intercept  (b) the slope  (c) a constant term',
        'Study Tips'        => 'Your accuracy drops after about 45 minutes. Try 40-minute blocks with a 7-minute break — and open with Chemistry, it is your weakest subject at 61%.',
    ];

    /* Avatar initial for a teacher. Strips an honorific first, so "Mr. Rahul
       Verma" gives R and not M — and it still works for a bare "Priya Nair". */
    $initial = function (string $name): string {
        $clean = preg_replace('/^(mr|mrs|ms|miss|dr|prof)\.?\s+/i', '', trim($name));
        return mb_strtoupper(mb_substr($clean !== '' ? $clean : $name, 0, 1));
    };

    /* One payload for the JS. Nothing here is secret — it is the same sample
       data the markup was rendered from. */
    $payload = [
        'tasks'    => $tasks,
        'progress' => $progress,
        'chips'    => $tutorChips,
    ];
@endphp

@section('title', 'Learning Dashboard — schoolar.ai')
@section('page-title', $user['greeting'] . ', ' . $user['name'] . '! 👋')

@section('page-sub')
You have <b id="ldPending">{{ $taskCount - $doneCount }}</b> tasks to focus on today.
@endsection

{{-- Block form, not the inline parenthesised form. An inline php directive
     sitting above a block one gets swallowed by Blade's lazy raw-block regex
     and compiles to an invalid open tag — see the note at the top. --}}
@php
    $active = 'learning-dashboard';
@endphp

{{-- ═══════════════ Topbar slot: space switcher + search ═══════════════ --}}
@section('topbar-extra')
<div class="rel ld-topslot">
    <button type="button" class="ld-space" id="ldSpaceBtn" aria-haspopup="true" aria-expanded="false">
        <span id="ldSpaceLabel">{{ $spaces[0]['name'] }}</span>
        <svg class="cv" width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><use href="#li-caret"/></svg>
    </button>
    <div class="dropdown left ld-spacemenu" id="ldSpaceMenu">
        <div class="dhead">My Learning Spaces</div>
        @foreach ($spaces as $s)
            <button type="button" class="ld-spaceitem @if($loop->first) on @endif"
                    data-space="{{ $s['id'] }}" data-name="{{ $s['name'] }}">
                <span class="tile"><svg width="16" height="16" viewBox="0 0 24 24"><use href="#li-{{ $s['icon'] }}"/></svg></span>
                <span class="txt"><b>{{ $s['name'] }}</b><span>{{ $s['sub'] }}</span></span>
                <svg class="ok" width="15" height="15" viewBox="0 0 24 24"><use href="#li-check"/></svg>
            </button>
        @endforeach
        <div class="ld-sep"></div>
        <button type="button" class="ld-spaceadd" onclick="showToast('Add Learning Space — hook this up to your create-space flow')">
            <svg width="14" height="14" viewBox="0 0 24 24"><use href="#li-plus"/></svg> Add Learning Space
        </button>
    </div>
</div>

<label class="ld-search">
    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><use href="#li-search"/></svg>
    <input id="ldSearch" type="search" placeholder="Search for topics, tests, notes..." autocomplete="off" aria-label="Search today's plan">
</label>
@endsection

{{-- ═══════════════════════════ Styles ═══════════════════════════ --}}
@push('styles')
@verbatim
<style>
/* ══════════════ Topbar additions (live outside .ldash) ══════════════ */
/* The design puts the learning-space switcher where the exam pill sits on
   the other pages, so the pill is hidden here only. Other pages keep it. */
#testPill{display:none}

.ld-space{display:flex;align-items:center;gap:9px;background:#fff;
    border:1px solid var(--border);border-radius:11px;height:38px;padding:0 13px;
    font-size:12.5px;font-weight:700;font-family:inherit;color:var(--text);
    cursor:pointer;white-space:nowrap;min-width:172px}
.ld-space:hover{border-color:var(--indigo)}
.ld-space .cv{margin-left:auto;color:var(--muted);flex-shrink:0}
.ld-spacemenu{width:262px;padding:7px}
.ld-spaceitem{display:flex;align-items:center;gap:10px;width:100%;padding:8px 9px;
    border:0;border-radius:10px;background:none;text-align:left;font-family:inherit;
    color:var(--text);cursor:pointer}
.ld-spaceitem:hover{background:#F6F7FB}
.ld-spaceitem .tile{width:31px;height:31px;border-radius:9px;display:grid;place-items:center;
    background:#F1F2F8;color:#5C6180;flex-shrink:0}
.ld-spaceitem.on .tile{background:var(--indigo);color:#fff}
.ld-spaceitem .txt{min-width:0;flex:1}
.ld-spaceitem .txt b{display:block;font-size:12.5px;font-weight:700;line-height:1.3}
.ld-spaceitem .txt span{display:block;font-size:11px;color:var(--muted)}
.ld-spaceitem .ok{color:var(--indigo);opacity:0;flex-shrink:0}
.ld-spaceitem.on .ok{opacity:1}
.ld-sep{height:1px;background:var(--border);margin:6px 4px}
.ld-spaceadd{display:flex;align-items:center;gap:8px;width:100%;padding:8px 9px;border:0;
    border-radius:10px;background:none;color:var(--indigo);font-family:inherit;
    font-size:12.5px;font-weight:700;cursor:pointer}
.ld-spaceadd:hover{background:#F1EEFF}

.ld-search{display:flex;align-items:center;gap:8px;background:#fff;
    border:1px solid var(--border);border-radius:11px;height:38px;padding:0 13px;
    width:250px;min-width:0}
.ld-search:focus-within{border-color:var(--indigo)}
.ld-search svg{color:var(--muted);flex-shrink:0}
.ld-search input{flex:1;min-width:0;width:100%;border:0;outline:0;background:none;
    font-family:inherit;font-size:12.5px;color:var(--text)}
.ld-search input::placeholder{color:#A3A7BD}
.ld-search input::-webkit-search-cancel-button{cursor:pointer}

@media(max-width:1320px){ .ld-search{width:190px} .ld-space{min-width:150px} }
@media(max-width:1120px){ .ld-search{display:none} }
@media(max-width:940px){  .ld-topslot{display:none} }

/* ═══════════════════════ Page: design tokens ═══════════════════════ */
/* Declared on .ldash, NOT :root — the shell owns the global tokens. */
.ldash{
    --l-card:#FFFFFF; --l-ink:#12142B; --l-ink2:#3C3F58; --l-muted:#767B94;
    --l-line:#ECEEF6; --l-line2:#E1E4EF;
    --l-violet:#5A4CF0; --l-violet2:#7C5CF6; --l-violet-soft:#EFECFE;
    --l-teal:#0FAF8C; --l-teal2:#14C79E; --l-teal-soft:#E2F6F1;
    --l-amber-soft:#FEF3E2; --l-rose:#F0446E; --l-rose-soft:#FEE9EF;
    --l-orange-soft:#FFEFE5;
    --l-shadow:0 1px 2px rgba(20,20,50,.04),0 8px 24px rgba(20,20,50,.05);
    --l-r:16px;
    color:var(--l-ink);
    font-size:14px;
    line-height:1.45;
}
.ldash *{box-sizing:border-box;margin:0;padding:0}
.ldash button{font-family:inherit;color:inherit;background:none;border:0;cursor:pointer}
.ldash input{font-family:inherit;color:inherit}
.ldash a{color:inherit;text-decoration:none}
.ldash svg{display:block}
.ldash .sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
.ldash [hidden]{display:none !important}

/* ═════════════════════════ Page columns ═════════════════════════ */
/* minmax(0,1fr) rather than 1fr is what stops a wide child (the chart, a
   long task title) from forcing the whole grid wider than the screen. */
.ldash .cols{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start}
.ldash .col{display:flex;flex-direction:column;gap:18px;min-width:0}

.ldash .card{background:var(--l-card);border:1px solid var(--l-line);
    border-radius:var(--l-r);box-shadow:var(--l-shadow);min-width:0}
.ldash .card-h{display:flex;align-items:center;gap:10px;padding:16px 18px 0;min-width:0}
.ldash .eyebrow{font-size:11px;font-weight:800;letter-spacing:.7px;text-transform:uppercase;
    color:var(--l-ink);white-space:nowrap}
.ldash .link{margin-left:auto;font-size:12px;font-weight:700;color:var(--l-violet);
    white-space:nowrap;flex-shrink:0}
.ldash .link:hover{text-decoration:underline}
.ldash .pip{width:8px;height:8px;border-radius:3px;flex-shrink:0}

/* ───────────────────── Learning overview ───────────────────── */
.ldash .ov-wrap{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}
.ldash .ov{padding:16px 18px 18px;display:flex;gap:16px;align-items:center;min-width:0}
.ldash .ov + .ov{border-left:1px solid var(--l-line)}
/* When the space filter hides one half, the survivor takes the full width
   instead of leaving a dead column. */
.ldash .ov-wrap:has(> .ov[hidden]){grid-template-columns:minmax(0,1fr)}
.ldash .ov-wrap:has(> .ov[hidden]) .ov{border-left:0}
.ldash .ov-main{min-width:0;flex:1}
.ldash .ov-title{font-size:13px;font-weight:800;display:flex;align-items:center;gap:8px;min-width:0}
.ldash .ov-title .tile{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;flex-shrink:0}
.ldash .ov-title b{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .ov-label{font-size:11.5px;color:var(--l-muted);margin:6px 0 9px}
.ldash .ov-num{font-size:30px;font-weight:800;letter-spacing:-1px;line-height:1}
.ldash .ov-num small{font-size:15px;font-weight:800;letter-spacing:0}
.ldash .ov-delta{display:inline-flex;align-items:center;gap:4px;font-size:11.5px;
    font-weight:700;margin-top:7px;color:var(--l-teal)}
.ldash .ov-side{display:flex;align-items:center;gap:12px;flex-shrink:0}
.ldash .ov-spark{display:flex;flex-direction:column;align-items:flex-end;gap:6px}
.ldash .ov-foot{text-align:right}
.ldash .ov-foot b{font-size:14px;font-weight:800;display:block;white-space:nowrap}
.ldash .ov-foot span{font-size:11px;color:var(--l-muted)}

.ldash .donut{transform:rotate(-90deg)}
.ldash .donut circle{fill:none;stroke-width:9;stroke-linecap:round}
.ldash .donut .track{stroke:#EDEFF6}
.ldash .donut .val{transition:stroke-dasharray .55s cubic-bezier(.3,.9,.3,1)}
.ldash .donut-wrap{position:relative;display:grid;place-items:center;flex-shrink:0}
.ldash .donut-mid{position:absolute;text-align:center;font-size:15px;font-weight:800;line-height:1.2}

/* ───────────────────────── Today's plan ────────────────────── */
.ldash .plan-meta{display:flex;align-items:center;gap:8px;font-size:11.5px;
    color:var(--l-muted);font-weight:700;white-space:nowrap}
.ldash .plan-meta .sep{width:3px;height:3px;border-radius:50%;background:#CBD0E0;flex-shrink:0}
.ldash .plan-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;padding:14px 18px 18px}
.ldash .task{display:flex;align-items:center;gap:11px;padding:11px 12px;
    border:1px solid var(--l-line);border-radius:12px;background:#FCFCFE;min-width:0;
    transition:border-color .15s,background .15s}
.ldash .task:hover{border-color:#D9DCEA;background:#fff}
.ldash .task.done{background:#F7F8FC}
.ldash .task.done .task-title{color:var(--l-muted);text-decoration:line-through}
.ldash .task .tile{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0}
.ldash .task-body{min-width:0;flex:1}
.ldash .task-tagline{display:flex;align-items:center;gap:7px;margin-bottom:3px;min-width:0}
.ldash .pill{font-size:9px;font-weight:800;letter-spacing:.5px;text-transform:uppercase;
    padding:2.5px 6px;border-radius:5px;flex-shrink:0}
.ldash .pill.school{background:var(--l-teal-soft);color:var(--l-teal)}
.ldash .pill.neet{background:var(--l-violet-soft);color:var(--l-violet)}
.ldash .task-title{font-size:13px;font-weight:700;line-height:1.3;min-width:0;
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .task-sub{font-size:11.5px;color:var(--l-muted);overflow:hidden;
    text-overflow:ellipsis;white-space:nowrap}
.ldash .task-mins{font-size:11.5px;font-weight:700;color:var(--l-muted);
    white-space:nowrap;flex-shrink:0}
.ldash .tick{width:21px;height:21px;border-radius:50%;border:2px solid #D3D7E6;
    flex-shrink:0;display:grid;place-items:center;color:#fff;transition:.16s;background:none}
.ldash .tick:hover{border-color:var(--l-violet2)}
.ldash .task.done .tick{background:var(--l-teal);border-color:var(--l-teal)}
.ldash .tick svg{opacity:0;transition:opacity .16s}
.ldash .task.done .tick svg{opacity:1}
.ldash .empty{grid-column:1/-1;text-align:center;padding:26px 10px;color:var(--l-muted);
    font-size:13px;border:1px dashed var(--l-line2);border-radius:12px}

/* ─────────────────────── Continue learning ─────────────────── */
.ldash .cont-body{padding:14px 18px 18px;display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.ldash .cont-col{min-width:0}
.ldash .cont-body:has(> .cont-col[hidden]){grid-template-columns:minmax(0,1fr)}
.ldash .cont-head{display:flex;align-items:center;gap:7px;font-size:12px;font-weight:800;
    padding-bottom:9px;border-bottom:1px solid var(--l-line);margin-bottom:4px;min-width:0}
.ldash .cont-head .tile{width:22px;height:22px;border-radius:7px;display:grid;place-items:center;flex-shrink:0}
.ldash .cont-head b{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0}
.ldash .cont-head .link{font-size:11px}
.ldash .cont-item{display:flex;align-items:center;gap:10px;padding:9px 0;min-width:0}
.ldash .cont-item .tile{width:30px;height:30px;border-radius:9px;display:grid;place-items:center;flex-shrink:0}
.ldash .cont-txt{min-width:0;flex:1}
.ldash .cont-txt b{display:block;font-size:12.5px;font-weight:700;line-height:1.3;
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .cont-txt small{display:block;font-size:11px;color:var(--l-muted);margin-bottom:6px}
.ldash .cont-pct{font-size:11.5px;font-weight:800;color:var(--l-muted);
    white-space:nowrap;flex-shrink:0;align-self:flex-start;padding-top:2px}
.ldash .bar{height:5px;border-radius:99px;background:#EDEFF6;overflow:hidden}
.ldash .bar i{display:block;height:100%;border-radius:99px;transition:width .5s cubic-bezier(.3,.9,.3,1)}

/* ───────────────────────── My progress ─────────────────────── */
.ldash .prog-body{padding:12px 18px 18px}
.ldash .prog-h{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:4px}
.ldash .legend{display:flex;align-items:center;gap:13px;font-size:11px;font-weight:700;color:var(--l-muted)}
.ldash .legend i{display:inline-block;width:9px;height:9px;border-radius:3px;margin-right:5px;vertical-align:-1px}
.ldash .seg{display:flex;gap:2px;background:#F1F2F8;border-radius:9px;padding:3px;margin-left:auto}
.ldash .seg button{padding:5px 11px;border-radius:7px;font-size:11.5px;font-weight:700;color:var(--l-muted)}
.ldash .seg button.on{background:#fff;color:var(--l-ink);box-shadow:0 1px 3px rgba(20,20,50,.1)}
/* The chart is a block-level SVG with no intrinsic width, so it can never
   push the grid wider — it is measured and redrawn to fit instead. */
.ldash .chart{display:block;width:100%;height:186px}
.ldash .chart .grid-l{stroke:#EFF1F7;stroke-width:1}
.ldash .chart .axis{fill:#A3A7BD;font-size:10px;font-weight:600}
.ldash .chart .ln{fill:none;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round}
.ldash .chart .pt{stroke:#fff;stroke-width:2}
.ldash .prog-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:9px;margin-top:12px}
.ldash .stat{background:#F8F9FD;border:1px solid var(--l-line);border-radius:11px;
    padding:10px 11px;min-width:0}
.ldash .stat b{font-size:18px;font-weight:800;letter-spacing:-.4px;white-space:nowrap}
.ldash .stat b small{font-size:11px;font-weight:700;color:var(--l-muted);letter-spacing:0}
.ldash .stat span{display:block;font-size:10.5px;color:var(--l-muted);font-weight:600;
    margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

/* ──────────────── AI tutor + smart recommendation ──────────── */
.ldash .duo{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;align-items:stretch}
.ldash .tutor{padding:16px 18px 18px;display:flex;flex-direction:column;gap:12px;min-width:0}
.ldash .tutor-top{display:flex;gap:12px;min-width:0}
.ldash .bot{width:46px;height:46px;border-radius:13px;flex-shrink:0;display:grid;
    place-items:center;font-size:23px;background:linear-gradient(135deg,var(--l-violet-soft),#E6F6FF)}
.ldash .tutor-top b{font-size:13.5px;font-weight:800;display:block}
.ldash .tutor-top span{font-size:11.5px;color:var(--l-muted)}
.ldash .chips{display:flex;flex-wrap:wrap;gap:7px}
.ldash .chip{padding:6px 11px;border:1px solid var(--l-line2);border-radius:99px;
    font-size:11.5px;font-weight:700;color:var(--l-ink2);transition:.14s}
.ldash .chip:hover{border-color:var(--l-violet2);color:var(--l-violet);background:var(--l-violet-soft)}
.ldash .thread{display:flex;flex-direction:column;gap:8px;max-height:150px;
    overflow-y:auto;overflow-x:hidden;padding-right:3px}
.ldash .thread:empty{display:none}
.ldash .thread::-webkit-scrollbar{width:5px}
.ldash .thread::-webkit-scrollbar-thumb{background:#DDE0EC;border-radius:99px}
.ldash .msg{max-width:88%;padding:9px 12px;border-radius:13px;font-size:12.5px;
    line-height:1.45;overflow-wrap:anywhere}
.ldash .msg.me{align-self:flex-end;background:var(--l-violet);color:#fff;border-bottom-right-radius:5px}
.ldash .msg.ai{align-self:flex-start;background:#F4F5FA;color:var(--l-ink2);border-bottom-left-radius:5px}
.ldash .ask{display:flex;align-items:center;gap:9px;border:1px solid var(--l-line2);
    border-radius:12px;padding:0 6px 0 13px;height:44px;margin-top:auto;min-width:0}
.ldash .ask:focus-within{border-color:var(--l-violet2)}
.ldash .ask input{flex:1;min-width:0;width:100%;border:0;outline:0;background:none;font-size:13px}
.ldash .send{width:32px;height:32px;border-radius:9px;background:var(--l-violet);
    color:#fff;display:grid;place-items:center;flex-shrink:0}
.ldash .send:hover{background:#4636D6}

.ldash .smart{padding:16px 18px 18px;display:flex;flex-direction:column;gap:11px;
    position:relative;overflow:hidden;min-width:0}
.ldash .smart .flask{position:absolute;right:14px;bottom:10px;font-size:46px;
    opacity:.22;pointer-events:none}
.ldash .badge-new{font-size:9px;font-weight:800;letter-spacing:.4px;text-transform:uppercase;
    background:var(--l-violet-soft);color:var(--l-violet);padding:2.5px 7px;border-radius:6px}
.ldash .smart p{font-size:12.5px;color:var(--l-ink2);line-height:1.55;max-width:76%}
.ldash .btn{display:inline-flex;align-items:center;gap:7px;align-self:flex-start;height:38px;
    padding:0 16px;border-radius:10px;font-size:12.5px;font-weight:800;
    background:var(--l-violet);color:#fff;transition:background .15s;margin-top:auto}
.ldash .btn:hover{background:#4636D6}

/* ═══════════════════════ Right rail ═══════════════════════ */
.ldash .rail-body{padding:14px 16px 16px}
.ldash .sum-top{display:flex;align-items:center;gap:13px;margin-bottom:12px;min-width:0}
.ldash .sum-top b{font-size:14px;font-weight:800;display:block}
.ldash .sum-top span{font-size:11.5px;color:var(--l-muted)}
.ldash .sum-item{display:flex;align-items:center;gap:9px;width:100%;padding:7px 0;
    text-align:left;min-width:0}
.ldash .sum-item .tick{width:18px;height:18px}
.ldash .sum-item b{font-size:12.5px;font-weight:600;flex:1;min-width:0;
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .sum-item span{font-size:11px;color:var(--l-muted);white-space:nowrap;flex-shrink:0}
.ldash .sum-item.done b{color:var(--l-muted);text-decoration:line-through}
.ldash .sum-item.done .tick{background:var(--l-teal);border-color:var(--l-teal)}
.ldash .sum-item.done .tick svg{opacity:1}

/* Rows are separated by whitespace, not rules — the reference card has no
   divider lines between classes. */
.ldash .cls{display:flex;gap:12px;padding:13px 0;min-width:0}
.ldash .cls:first-of-type{padding-top:6px}
.ldash .cls:last-of-type{padding-bottom:2px}
.ldash .cls .who{width:46px;height:46px;border-radius:10px;flex-shrink:0;display:grid;
    place-items:center;font-size:16px;font-weight:800;color:#fff}
.ldash .cls-body{min-width:0;flex:1}
.ldash .cls-tag{display:flex;align-items:center;gap:7px;margin-bottom:3px;min-width:0}
/* Solid violet chip, no blinking dot — it reads as a label, not an alarm. */
.ldash .live{display:inline-flex;align-items:center;font-size:9px;font-weight:800;
    line-height:1;letter-spacing:.5px;background:var(--l-violet);color:#fff;
    padding:4px 6px;border-radius:5px;flex-shrink:0}
.ldash .cls-body b{font-size:13px;font-weight:800;color:var(--l-ink);display:block;
    line-height:1.25;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
/* Topic and teacher get a line each, as in the reference. */
.ldash .cls-body small{display:block;font-size:11.5px;line-height:1.5;color:var(--l-ink2);
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .cls-body small + small{color:var(--l-muted)}
.ldash .cls-when{display:flex;align-items:center;gap:5px;font-size:11px;
    color:var(--l-muted);margin-top:4px;white-space:nowrap}
/* Soft-tinted button: secondary weight, so the card header stays the anchor. */
.ldash .join{height:30px;padding:0 16px;border-radius:9px;background:var(--l-violet-soft);
    color:var(--l-violet);font-size:11.5px;font-weight:800;align-self:center;
    flex-shrink:0;transition:background .15s,color .15s}
.ldash .join:hover{background:var(--l-violet);color:#fff}
.ldash .join.joined{background:var(--l-teal-soft);color:var(--l-teal)}

.ldash .ach-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:9px}
.ldash .ach{border-radius:12px;padding:12px 6px;text-align:center;min-width:0}
.ldash .ach .ico{font-size:19px;line-height:1.2}
.ldash .ach b{display:block;font-size:12px;font-weight:800;margin-top:5px;
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .ach span{display:block;font-size:10px;color:var(--l-muted);margin-top:1px;
    overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ldash .ach.orange{background:var(--l-orange-soft)}
.ldash .ach.amber{background:var(--l-amber-soft)}
.ldash .ach.rose{background:var(--l-rose-soft)}

.ldash .ann{border-radius:12px;padding:14px;color:#fff;position:relative;overflow:hidden;
    background:linear-gradient(135deg,var(--l-violet2),#9B6BFF)}
.ldash .ann .trophy{position:absolute;right:10px;top:9px;font-size:30px;opacity:.35}
.ldash .ann b{display:block;font-size:13px;font-weight:800;max-width:76%}
.ldash .ann small{display:block;font-size:11px;opacity:.9;margin-top:3px}
.ldash .ann .go{display:inline-flex;align-items:center;gap:5px;margin-top:10px;background:#fff;
    color:var(--l-violet);height:30px;padding:0 12px;border-radius:8px;font-size:11.5px;font-weight:800}

/* ═════════════════════════ Responsive ═════════════════════════
   Breakpoints line up with the shell's own (1180 / 940) so the page
   reflows at the same moment the sidebar collapses — never between.
   Every step drops to fewer columns; nothing ever scrolls sideways. */
@media(max-width:1420px){
    .ldash .prog-stats{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:1260px){
    .ldash .duo{grid-template-columns:minmax(0,1fr)}
    .ldash .smart p{max-width:100%}
}
@media(max-width:1180px){
    .ldash .cols{grid-template-columns:minmax(0,1fr)}
    .ldash .prog-stats{grid-template-columns:repeat(4,minmax(0,1fr))}
}
@media(max-width:980px){
    .ldash .plan-grid{grid-template-columns:minmax(0,1fr)}
    .ldash .cont-body{grid-template-columns:minmax(0,1fr)}
}
@media(max-width:820px){
    .ldash .ov-wrap{grid-template-columns:minmax(0,1fr)}
    .ldash .ov + .ov{border-left:0;border-top:1px solid var(--l-line)}
    .ldash .prog-stats{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:620px){
    .ldash .card-h{flex-wrap:wrap;row-gap:4px}
    .ldash .link{margin-left:0}
    .ldash .ov{flex-direction:column;align-items:flex-start;gap:14px}
    .ldash .ov-side{width:100%;justify-content:space-between}
    .ldash .ov-foot{text-align:left}
    .ldash .ach-grid{grid-template-columns:minmax(0,1fr)}
    .ldash .msg{max-width:100%}
}
@media (prefers-reduced-motion:reduce){
    .ldash *{animation-duration:.01ms !important;transition-duration:.01ms !important}
}
</style>
@endverbatim
@endpush

{{-- ═══════════════════════════ Content ═══════════════════════════ --}}
@section('content')

{{-- icon sprite --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs>
  <g id="li-search" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.6-3.6"/></g>
  <g id="li-caret" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></g>
  <g id="li-grid" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3" y="3" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="2"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="2"/></g>
  <g id="li-book" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5.5A2.5 2.5 0 0 1 5.5 3H10a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H3z"/><path d="M21 5.5A2.5 2.5 0 0 0 18.5 3H14a2 2 0 0 0-2 2v14a2 2 0 0 1 2-2h7z"/></g>
  <g id="li-target" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.5"/></g>
  <g id="li-plus" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></g>
  <g id="li-check" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7"/></g>
  <g id="li-up" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5.5 11.5 6.5-6.5 6.5 6.5"/></g>
  <g id="li-clock" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5.2l3.2 2"/></g>
  <g id="li-flask" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 3h5"/><path d="M10.5 3v6L5.6 17.4A2.4 2.4 0 0 0 7.7 21h8.6a2.4 2.4 0 0 0 2.1-3.6L13.5 9V3"/><path d="M8 15h8"/></g>
  <g id="li-leaf" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20c0-9 5.5-15 16-15 0 10-5.5 15-13 15H4z"/><path d="M4.5 19.5C8 16 11 13.5 15 11.5"/></g>
  <g id="li-atom" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="1.9"/><ellipse cx="12" cy="12" rx="9" ry="4"/><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(120 12 12)"/></g>
  <g id="li-pen" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4l10-10a2.8 2.8 0 0 0-4-4L4 16z"/><path d="m13.5 6.5 4 4"/></g>
  <g id="li-doc" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="m9 14.5 1.8 1.8 3.4-3.6"/></g>
  <g id="li-send" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 3 10.5 13.5"/><path d="M21 3l-6.8 18-3.7-7.5L3 9.8z"/></g>
  <g id="li-arrow" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h13"/><path d="m12.5 6 6 6-6 6"/></g>
  <g id="li-spark" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/></g>
</defs></svg>

<div class="ldash">
<div class="cols">

  {{-- ═══════════════════ LEFT COLUMN ═══════════════════ --}}
  <div class="col">

    {{-- ── Your learning overview ── --}}
    <section class="card">
      <div class="card-h"><span class="eyebrow">Your Learning Overview</span></div>
      <div class="ov-wrap">
        @foreach ($overview as $o)
          @php
              $C    = 2 * M_PI * 38;                    /* donut circumference */
              $dash = round($C * $o['value'] / 100, 2);
              $max  = max($o['spark']);
              $min  = min($o['spark']);
              $pts  = [];
              foreach ($o['spark'] as $i => $v) {
                  $x = round($i / max(count($o['spark']) - 1, 1) * 92, 2);
                  $y = round(30 - (($v - $min) / max($max - $min, 1)) * 26, 2);
                  $pts[] = "$x,$y";
              }
              $spark = implode(' ', $pts);
          @endphp
          <article class="ov" data-space="{{ $o['space'] }}">
            <div class="ov-main">
              <div class="ov-title">
                <span class="tile" style="background:var(--l-{{ $o['tone'] }}-soft);color:var(--l-{{ $o['tone'] }})">
                  <svg width="15" height="15" viewBox="0 0 24 24"><use href="#li-{{ $o['icon'] }}"/></svg>
                </span>
                <b>{{ $o['title'] }}</b>
              </div>
              <div class="ov-label">{{ $o['label'] }}</div>
              <div class="ov-num">{{ $o['value'] }}<small>{{ $o['unit'] }}</small></div>
              <div class="ov-delta">
                <svg width="11" height="11" viewBox="0 0 24 24"><use href="#li-up"/></svg>
                {{ $o['delta'] }}% {{ $o['deltaNote'] }}
              </div>
            </div>

            <div class="ov-side">
              <div class="donut-wrap">
                <svg class="donut" width="84" height="84" viewBox="0 0 100 100">
                  <circle class="track" cx="50" cy="50" r="38"/>
                  <circle class="val" cx="50" cy="50" r="38"
                          stroke="var(--l-{{ $o['tone'] }})"
                          stroke-dasharray="{{ $dash }} {{ round($C, 2) }}"/>
                </svg>
              </div>
              <div class="ov-spark">
                <svg width="92" height="32" viewBox="0 0 92 32" aria-hidden="true">
                  <polyline points="{{ $spark }}" fill="none"
                            stroke="var(--l-{{ $o['tone'] }})" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="ov-foot">
                  <b>{{ $o['footValue'] }}</b><span>{{ $o['footNote'] }}</span>
                </div>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    {{-- ── Today's plan ── --}}
    <section class="card">
      <div class="card-h">
        <span class="eyebrow">Today's Plan</span>
        <span class="plan-meta">
          <span class="sep"></span>{{ $taskCount }} tasks
          <span class="sep"></span>
          <svg width="12" height="12" viewBox="0 0 24 24"><use href="#li-clock"/></svg>
          {{ $taskTime }}
        </span>
        <button type="button" class="link" onclick="showToast('Opening the full weekly plan')">View Full Plan →</button>
      </div>

      <div class="plan-grid" id="ldPlanGrid">
        @foreach ($tasks as $t)
          @php $tone = $t['tag'] === 'school' ? 'teal' : 'violet'; @endphp
          <article class="task {{ $t['done'] ? 'done' : '' }}"
                   data-task="{{ $t['id'] }}"
                   data-space="{{ $t['space'] }}"
                   data-search="{{ strtolower($t['title'] . ' ' . $t['sub'] . ' ' . $t['tag']) }}">
            <span class="tile" style="background:var(--l-{{ $tone }}-soft);color:var(--l-{{ $tone }})">
              <svg width="18" height="18" viewBox="0 0 24 24"><use href="#li-{{ $t['icon'] }}"/></svg>
            </span>
            <div class="task-body">
              <div class="task-tagline">
                <span class="pill {{ $t['tag'] }}">{{ $t['tag'] }}</span>
                <span class="task-title">{{ $t['title'] }}</span>
              </div>
              <div class="task-sub">{{ $t['sub'] }}</div>
            </div>
            <span class="task-mins">{{ $t['mins'] }} min</span>
            <button type="button" class="tick" data-ldtoggle="{{ $t['id'] }}"
                    aria-pressed="{{ $t['done'] ? 'true' : 'false' }}"
                    aria-label="Mark {{ $t['title'] }} complete">
              <svg width="11" height="11" viewBox="0 0 24 24"><use href="#li-check"/></svg>
            </button>
          </article>
        @endforeach
        <p class="empty" id="ldPlanEmpty" hidden>Nothing matches that search.</p>
      </div>
    </section>

    {{-- ── Continue learning + My progress (side by side, as in the design) ── --}}
    <div class="duo">

      <section class="card">
        <div class="card-h">
          <span class="eyebrow">Continue Learning</span>
          <button type="button" class="link" onclick="showToast('Showing everything in progress')">View All →</button>
        </div>
        <div class="cont-body">
          @foreach ($continue as $g)
            <div class="cont-col" data-space="{{ $g['space'] }}">
              <div class="cont-head">
                <span class="tile" style="background:var(--l-{{ $g['tone'] }}-soft);color:var(--l-{{ $g['tone'] }})">
                  <svg width="13" height="13" viewBox="0 0 24 24"><use href="#li-{{ $g['icon'] }}"/></svg>
                </span>
                <b>{{ $g['title'] }}</b>
                <button type="button" class="link" onclick="showToast('Showing all {{ $g['total'] }} in {{ $g['title'] }}')">See all ({{ $g['total'] }})</button>
              </div>
              @foreach ($g['items'] as $it)
                <div class="cont-item">
                  <span class="tile" style="background:var(--l-{{ $g['tone'] }}-soft);color:var(--l-{{ $g['tone'] }})">
                    <svg width="15" height="15" viewBox="0 0 24 24"><use href="#li-{{ $it['icon'] }}"/></svg>
                  </span>
                  <div class="cont-txt">
                    <b>{{ $it['name'] }}</b>
                    <small>{{ $it['sub'] }}</small>
                    <div class="bar"><i style="width:{{ $it['pct'] }}%;background:var(--l-{{ $g['tone'] }})"></i></div>
                  </div>
                  <span class="cont-pct">{{ $it['pct'] }}%</span>
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      </section>

      <section class="card">
        <div class="card-h">
          <span class="eyebrow">My Progress</span>
          <span class="legend" style="margin-left:auto">
            <span><i style="background:var(--l-teal)"></i>School</span>
            <span><i style="background:var(--l-violet2)"></i>NEET</span>
          </span>
        </div>
        <div class="prog-body">
          <div class="prog-h">
            <div class="seg" id="ldPeriodSeg">
              <button type="button" class="on" data-period="week">This Week</button>
              <button type="button" data-period="month">This Month</button>
            </div>
          </div>
          {{-- viewBox is set from the real pixel width in JS, so axis text stays
               crisp and the data points stay circular at every screen size. --}}
          <svg class="chart" id="ldChart" role="img" aria-label="Progress over time, School versus NEET"></svg>
          <div class="prog-stats" id="ldProgStats"></div>
        </div>
      </section>
    </div>

    {{-- ── AI tutor + Smart recommendation ── --}}
    <div class="duo">
      <section class="card tutor">
        <div class="tutor-top">
          <span class="bot">🤖</span>
          <span>
            <b>Hi {{ $user['name'] }}! I'm your AI Tutor.</b>
            <span>Ask me anything, or pick a suggestion.</span>
          </span>
        </div>
        <div class="chips">
          @foreach (array_keys($tutorChips) as $chip)
            <button type="button" class="chip" data-ldchip="{{ $chip }}">{{ $chip }}</button>
          @endforeach
        </div>
        <div class="thread" id="ldThread" aria-live="polite"></div>
        <form class="ask" id="ldAskForm">
          <input id="ldAskInput" type="text" placeholder="Ask me anything..." autocomplete="off" aria-label="Ask the AI tutor">
          <button class="send" type="submit" aria-label="Send">
            <svg width="15" height="15" viewBox="0 0 24 24"><use href="#li-send"/></svg>
          </button>
        </form>
      </section>

      <section class="card smart">
        <span class="flask">🧪</span>
        <div style="display:flex;align-items:center;gap:8px">
          <span class="eyebrow">Smart Recommendation</span>
          <span class="badge-new">New</span>
        </div>
        <p>{{ $recommendation['body'] }}</p>
        <button type="button" class="btn" onclick="showToast('Building a combined Chemistry plan for school + NEET')">
          <svg width="14" height="14" viewBox="0 0 24 24"><use href="#li-spark"/></svg>
          {{ $recommendation['cta'] }}
        </button>
      </section>
    </div>
  </div>

  {{-- ═══════════════════ RIGHT RAIL ═══════════════════ --}}
  <aside class="col">

    {{-- Today's plan summary --}}
    <section class="card">
      <div class="card-h">
        <span class="eyebrow">Today's Plan Summary</span>
        <button type="button" class="link" onclick="showToast(&quot;Opening today's plan&quot;)">View Plan →</button>
      </div>
      <div class="rail-body">
        <div class="sum-top">
          @php $RC = 2 * M_PI * 38; @endphp
          <div class="donut-wrap">
            <svg class="donut" width="62" height="62" viewBox="0 0 100 100">
              <circle class="track" cx="50" cy="50" r="38"/>
              <circle class="val" id="ldSumRing" cx="50" cy="50" r="38" stroke="var(--l-violet2)"
                      stroke-dasharray="{{ round($RC * $doneCount / max($taskCount, 1), 2) }} {{ round($RC, 2) }}"/>
            </svg>
            <div class="donut-mid" style="font-size:12.5px"><span id="ldSumFrac">{{ $doneCount }}/{{ $taskCount }}</span></div>
          </div>
          <span>
            <b id="ldSumDone">{{ $doneCount }}</b>
            <span>of {{ $taskCount }} tasks completed</span>
          </span>
        </div>

        <div>
          @foreach ($tasks as $t)
            <button type="button" class="sum-item {{ $t['done'] ? 'done' : '' }}"
                    data-ldtoggle="{{ $t['id'] }}" data-ldsum="{{ $t['id'] }}">
              <span class="tick"><svg width="10" height="10" viewBox="0 0 24 24"><use href="#li-check"/></svg></span>
              <b>{{ $t['title'] }}</b>
              <span>{{ $t['mins'] }} min</span>
            </button>
          @endforeach
        </div>
      </div>
    </section>

    {{-- Upcoming live classes --}}
    <section class="card">
      <div class="card-h">
        <span class="eyebrow">Upcoming Live Classes</span>
        <button type="button" class="link" onclick="showToast('Showing the full class schedule')">View All →</button>
      </div>
      <div class="rail-body" style="padding-top:2px">
        @foreach ($classes as $c)
          <div class="cls">
            <span class="who" style="background:linear-gradient(135deg,var(--l-{{ $c['tone'] }}),var(--l-{{ $c['tone'] }}2))">
              {{ $initial($c['teacher']) }}
            </span>
            <div class="cls-body">
              <div class="cls-tag">
                @if ($c['live'])<span class="live">LIVE</span>@endif
                <b>{{ $c['subject'] }}</b>
              </div>
              <small>{{ $c['topic'] }}</small>
              <small>{{ $c['teacher'] }}</small>
              <div class="cls-when">
                <svg width="12" height="12" viewBox="0 0 24 24"><use href="#li-clock"/></svg>{{ $c['when'] }}
              </div>
            </div>
            <button type="button" class="join" data-ldjoin="{{ $c['subject'] }}">Join</button>
          </div>
        @endforeach
      </div>
    </section>

    {{-- Achievements --}}
    <section class="card">
      <div class="card-h">
        <span class="eyebrow">Achievements</span>
        <button type="button" class="link" onclick="showToast(&quot;Showing every badge you've earned&quot;)">View All →</button>
      </div>
      <div class="rail-body">
        <div class="ach-grid">
          @foreach ($achievements as $a)
            <div class="ach {{ $a['tone'] }}">
              <div class="ico">{{ $a['icon'] }}</div>
              <b>{{ $a['value'] }}</b><span>{{ $a['label'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- Announcements --}}
    <section class="card">
      <div class="card-h">
        <span class="eyebrow">Announcements</span>
        <button type="button" class="link" onclick="showToast('Showing all announcements')">View All →</button>
      </div>
      <div class="rail-body">
        @foreach ($announcements as $n)
          <div class="ann">
            <span class="trophy">🏆</span>
            <b>{{ $n['title'] }}</b>
            <small>{{ $n['line1'] }}</small>
            <small>{{ $n['line2'] }}</small>
            <button type="button" class="go" onclick="showToast('Opening registration for {{ $n['title'] }}')">
              {{ $n['cta'] }} <svg width="12" height="12" viewBox="0 0 24 24"><use href="#li-arrow"/></svg>
            </button>
          </div>
        @endforeach
      </div>
    </section>
  </aside>

</div>
</div>
@endsection

{{-- ═══════════════════════════ Scripts ═══════════════════════════ --}}
@push('scripts')
<script>window.__LDASH__ = @json($payload);</script>
@verbatim
<script>
(function () {
  'use strict';

  var D  = window.__LDASH__ || {};
  var $  = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* The shell already owns a toast. Reuse it rather than shipping a second
     one — two fixed-position toasts would stack on top of each other. */
  var toast = window.showToast || function () {};

  /* ─────────────────── Learning-space switcher ─────────────────── */
  /* Reuses the shell's .dropdown / .open styling and its outside-click
     handler; this only owns the toggling and the filter it drives. */
  var spaceBtn  = $('#ldSpaceBtn'),
      spaceMenu = $('#ldSpaceMenu'),
      space     = 'all',
      query     = '';

  if (spaceBtn && spaceMenu) {
    spaceBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = spaceMenu.classList.contains('open');
      $$('.dropdown.open,.test-dropdown.open').forEach(function (d) { d.classList.remove('open'); });
      spaceMenu.classList.toggle('open', !open);
      spaceBtn.setAttribute('aria-expanded', String(!open));
    });
  }

  $$('.ld-spaceitem').forEach(function (opt) {
    opt.addEventListener('click', function () {
      space = opt.getAttribute('data-space');
      $('#ldSpaceLabel').textContent = opt.getAttribute('data-name');
      $$('.ld-spaceitem').forEach(function (o) { o.classList.toggle('on', o === opt); });
      if (spaceMenu) spaceMenu.classList.remove('open');
      if (spaceBtn)  spaceBtn.setAttribute('aria-expanded', 'false');
      applyFilters();
      toast('Showing ' + opt.getAttribute('data-name'));
    });
  });

  /* ───────────────────── Search + space filter ─────────────────── */
  function applyFilters() {
    var shown = 0;
    $$('#ldPlanGrid [data-task]').forEach(function (row) {
      var okSpace = space === 'all' || row.getAttribute('data-space') === space;
      var okQuery = !query || row.getAttribute('data-search').indexOf(query) > -1;
      var show = okSpace && okQuery;
      row.hidden = !show;
      if (show) shown++;
    });
    var empty = $('#ldPlanEmpty');
    if (empty) empty.hidden = shown !== 0;

    /* Overview halves and Continue-Learning columns follow the same filter. */
    $$('.ldash .ov[data-space], .ldash .cont-col[data-space]').forEach(function (el) {
      el.hidden = !(space === 'all' || el.getAttribute('data-space') === space);
    });
  }

  var searchInput = $('#ldSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      query = e.target.value.trim().toLowerCase();
      applyFilters();
    });
  }

  /* ─────────────────────── Task completion ─────────────────────── */
  var tasks = {};
  (D.tasks || []).forEach(function (t) { tasks[t.id] = !!t.done; });

  var TOTAL  = (D.tasks || []).length || 1;
  var RING_C = 2 * Math.PI * 38;

  function syncTasks() {
    var done = 0;
    Object.keys(tasks).forEach(function (id) { if (tasks[id]) done++; });

    $$('.ldash [data-task]').forEach(function (row) {
      var on = tasks[row.getAttribute('data-task')];
      row.classList.toggle('done', on);
      var tick = row.querySelector('.tick');
      if (tick) tick.setAttribute('aria-pressed', String(on));
    });
    $$('.ldash [data-ldsum]').forEach(function (row) {
      row.classList.toggle('done', !!tasks[row.getAttribute('data-ldsum')]);
    });

    var d1 = $('#ldSumDone'), d2 = $('#ldSumFrac'), d3 = $('#ldPending'), ring = $('#ldSumRing');
    if (d1) d1.textContent = done;
    if (d2) d2.textContent = done + '/' + TOTAL;
    if (d3) d3.textContent = TOTAL - done;
    if (ring) ring.setAttribute('stroke-dasharray',
              (RING_C * done / TOTAL).toFixed(2) + ' ' + RING_C.toFixed(2));
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('[data-ldtoggle]') : null;
    if (!btn) return;
    var id = btn.getAttribute('data-ldtoggle');
    tasks[id] = !tasks[id];
    syncTasks();
    toast(tasks[id] ? 'Marked complete' : 'Marked as still to do');
    /* Persist here, e.g.
       fetch('/tasks/' + id, {
         method: 'PATCH',
         headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
       }); */
  });

  /* ───────────────────────── Line chart ────────────────────────── */
  /* The viewBox is rebuilt from the SVG's real pixel width, so one user unit
     equals one screen pixel: axis text stays crisp and the points stay round.
     A fixed viewBox stretched to fit would distort both. */
  var H = 186, PAD_L = 34, PAD_R = 10, PAD_T = 10, PAD_B = 26;
  var chartEl = $('#ldChart');
  var period  = 'week';

  function drawChart(next) {
    if (!chartEl) return;
    if (next) period = next;
    var d = (D.progress || {})[period];
    if (!d) return;

    var W = Math.max(Math.round(chartEl.clientWidth) || 520, 260);
    chartEl.setAttribute('viewBox', '0 0 ' + W + ' ' + H);

    var n  = d.labels.length;
    var iw = W - PAD_L - PAD_R;
    var ih = H - PAD_T - PAD_B;
    var x  = function (i) { return PAD_L + (n === 1 ? iw / 2 : i / (n - 1) * iw); };
    var y  = function (v) { return PAD_T + ih - (v / 100) * ih; };
    var out = '';

    [0, 25, 50, 75, 100].forEach(function (v) {
      out += '<line class="grid-l" x1="' + PAD_L + '" y1="' + y(v) + '" x2="' + (W - PAD_R) + '" y2="' + y(v) + '"/>'
           + '<text class="axis" x="' + (PAD_L - 7) + '" y="' + (y(v) + 3.5) + '" text-anchor="end">' + v + '%</text>';
    });

    /* First and last x-labels hug the edges so they can never clip. */
    d.labels.forEach(function (l, i) {
      var anchor = i === 0 ? 'start' : (i === n - 1 ? 'end' : 'middle');
      out += '<text class="axis" x="' + x(i) + '" y="' + (H - 7) + '" text-anchor="' + anchor + '">' + l + '</text>';
    });

    [['school', 'var(--l-teal)', 'School'], ['neet', 'var(--l-violet2)', 'NEET']].forEach(function (s) {
      var key = s[0], colour = s[1], name = s[2], series = d[key] || [];
      var path = series.map(function (v, i) { return (i ? 'L' : 'M') + x(i) + ' ' + y(v); }).join(' ');
      out += '<path class="ln" d="' + path + '" stroke="' + colour + '"/>';
      series.forEach(function (v, i) {
        out += '<circle class="pt" cx="' + x(i) + '" cy="' + y(v) + '" r="3.4" fill="' + colour + '">'
             + '<title>' + d.labels[i] + ' — ' + name + ': ' + v + '%</title></circle>';
      });
    });

    chartEl.innerHTML = out;

    var stats = $('#ldProgStats');
    if (stats) {
      stats.innerHTML = d.stats.map(function (s) {
        return '<div class="stat"><b>' + s.value + (s.note ? ' <small>' + s.note + '</small>' : '')
             + '</b><span>' + s.label + '</span></div>';
      }).join('');
    }
  }

  var seg = $('#ldPeriodSeg');
  if (seg) {
    seg.addEventListener('click', function (e) {
      var b = e.target.closest('[data-period]');
      if (!b) return;
      $$('#ldPeriodSeg button').forEach(function (x) { x.classList.toggle('on', x === b); });
      drawChart(b.getAttribute('data-period'));
    });
  }

  /* Redraw on resize so the chart always re-fits its column. */
  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () { drawChart(); }, 140);
  });

  /* ────────────────────────── AI tutor ─────────────────────────── */
  var thread = $('#ldThread');

  function say(who, text) {
    if (!thread) return;
    var el = document.createElement('div');
    el.className = 'msg ' + who;
    el.textContent = text;
    thread.appendChild(el);
    thread.scrollTop = thread.scrollHeight;
  }
  function reply(text) { setTimeout(function () { say('ai', text); }, 320); }

  $$('[data-ldchip]').forEach(function (chip) {
    chip.addEventListener('click', function () {
      var label = chip.getAttribute('data-ldchip');
      say('me', label);
      reply((D.chips || {})[label] || 'Let me look at that.');
    });
  });

  var askForm = $('#ldAskForm');
  if (askForm) {
    askForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var input = $('#ldAskInput');
      var text  = input.value.trim();
      if (!text) return;
      say('me', text);
      input.value = '';
      reply('Good question about "' + text + '". Wire this form to your tutor endpoint and this bubble becomes the real answer.');
    });
  }

  /* ─────────────────────── Odds and ends ───────────────────────── */
  $$('[data-ldjoin]').forEach(function (b) {
    b.addEventListener('click', function () {
      b.textContent = 'Joined';
      b.classList.add('joined');
      toast('Joining ' + b.getAttribute('data-ldjoin') + ' — opening the class');
    });
  });

  /* ───────────────────────── Kick off ──────────────────────────── */
  syncTasks();
  drawChart('week');
  applyFilters();
})();
</script>
@endverbatim
@endpush