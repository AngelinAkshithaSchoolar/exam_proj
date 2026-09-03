@extends('layouts.app')

@section('title', 'Calendar')
@section('page-title', 'Calendar')
@section('page-sub', 'All your school, exam and study activities in one place.')

@php
    /*
    |--------------------------------------------------------------------------
    | PAGE DATA  —  seeded events
    |--------------------------------------------------------------------------
    | Same convention as performance.blade.php: the data lives here until a
    | controller feeds it. When you wire one up, delete this block and pass the
    | identical variable names:
    |
    |     return view('calendar', compact('calendarEvents', 'eventTypes'));
    |
    | $calendarEvents rows must keep this shape — the JS depends on the keys:
    |     id       string, unique
    |     title    string
    |     type     one of the keys in $eventTypes
    |     date     'Y-m-d'
    |     time     'H:i'  (24h) or null when all-day
    |     end      'H:i'  or null
    |     note     short subtitle, or ''
    |
    | Dates are generated relative to the CURRENT month so the page is never
    | empty. Swap the ->modify() calls for real dates once the DB is in place.
    */

    $eventTypes = [
        'school'   => ['label' => 'School',    'color' => '#2563EB', 'soft' => '#EAF1FE', 'icon' => '🏫'],
        'exams'    => ['label' => 'Exams',     'color' => '#E1483F', 'soft' => '#FDECEB', 'icon' => '📕'],
        'study'    => ['label' => 'Study',     'color' => '#12A150', 'soft' => '#E7F7EF', 'icon' => '📗'],
        'practice' => ['label' => 'Practice',  'color' => '#F7931E', 'soft' => '#FDF3E3', 'icon' => '🎯'],
        'ai-tutor' => ['label' => 'AI Tutor',  'color' => '#6366F1', 'soft' => '#EEF0FF', 'icon' => '🤖'],
        'important'=> ['label' => 'Important', 'color' => '#F5B301', 'soft' => '#FEF6E0', 'icon' => '⭐'],
    ];

    // First day of the current month — every seeded date is an offset from it.
    // Clamped to the month length so day 31 does not spill into the next month.
    $m    = new DateTimeImmutable(date('Y-m-01'));
    $days = (int) $m->format('t');
    $d    = fn (int $day) => $m->modify('+' . (min($day, $days) - 1) . ' days')->format('Y-m-d');

    $calendarEvents = [
        ['id' => 's1',  'type' => 'exams',     'title' => 'JEE Mock Test',        'note' => 'Full Length',        'date' => $d(1),  'time' => '10:00', 'end' => '13:00'],
        ['id' => 's2',  'type' => 'school',    'title' => 'Physics Class',        'note' => 'Laws of Motion',     'date' => $d(2),  'time' => '12:00', 'end' => '13:00'],
        ['id' => 's3',  'type' => 'study',     'title' => 'Homework Due',         'note' => 'Chapter 5',          'date' => $d(2),  'time' => '17:00', 'end' => null],
        ['id' => 's4',  'type' => 'practice',  'title' => 'Math Practice',        'note' => '30 Questions',       'date' => $d(7),  'time' => '16:00', 'end' => '17:00'],
        ['id' => 's5',  'type' => 'ai-tutor',  'title' => 'AI Tutor Session',     'note' => 'Weak Topics',        'date' => $d(9),  'time' => '18:00', 'end' => '19:00'],
        ['id' => 's6',  'type' => 'exams',     'title' => 'IBPS Clerk Mock Test', 'note' => 'Prelims',            'date' => $d(11), 'time' => '10:00', 'end' => '11:00'],
        ['id' => 's7',  'type' => 'school',    'title' => 'Chemistry Class',      'note' => 'Organic Basics',     'date' => $d(13), 'time' => '11:00', 'end' => '12:00'],
        ['id' => 's8',  'type' => 'study',     'title' => 'Biology Notes',        'note' => 'Revision',           'date' => $d(15), 'time' => '19:00', 'end' => '20:30'],
        ['id' => 's9',  'type' => 'important', 'title' => 'NEET Exam Date',       'note' => '',                   'date' => $d(19), 'time' => null,    'end' => null],
        ['id' => 's10', 'type' => 'school',    'title' => 'Maths Class',          'note' => 'Quadratic Equations','date' => $d(21), 'time' => '10:30', 'end' => '11:30'],
        ['id' => 's11', 'type' => 'study',     'title' => 'Assignment Due',       'note' => 'Trigonometry',       'date' => $d(21), 'time' => '23:59', 'end' => null],
        ['id' => 's12', 'type' => 'practice',  'title' => 'Previous Year Papers', 'note' => 'Set 3',              'date' => $d(23), 'time' => '19:00', 'end' => '21:00'],
        ['id' => 's13', 'type' => 'ai-tutor',  'title' => 'AI Tutor Session',     'note' => 'Concept Clarity',    'date' => $d(28), 'time' => '18:00', 'end' => '19:00'],
        ['id' => 's14', 'type' => 'exams',     'title' => 'PTE Practice Test',    'note' => 'Scored',             'date' => $d(31), 'time' => '09:30', 'end' => '12:00'],
        // A couple of entries on today itself so the schedule strip is populated.
        ['id' => 's15', 'type' => 'school',    'title' => 'Maths Class',          'note' => 'Quadratic Equations','date' => date('Y-m-d'), 'time' => '10:00', 'end' => '11:00'],
        ['id' => 's16', 'type' => 'study',     'title' => 'Physics Homework',     'note' => 'Chapter 5: Laws of Motion', 'date' => date('Y-m-d'), 'time' => '12:00', 'end' => '13:00'],
        ['id' => 's17', 'type' => 'practice',  'title' => 'Math Practice',        'note' => 'Algebra — 20 Questions',    'date' => date('Y-m-d'), 'time' => '16:00', 'end' => '17:00'],
        ['id' => 's18', 'type' => 'ai-tutor',  'title' => 'AI Tutor Session',     'note' => 'Weak Areas in Physics',     'date' => date('Y-m-d'), 'time' => '19:00', 'end' => '20:00'],
    ];
@endphp

@push('styles')
<style>
/* ==========================================================================
   CALENDAR PAGE  —  everything is scoped to .cal-page so nothing leaks into
   the shared layout, sidebar or topbar.
   ========================================================================== */
.cal-page{
    --c-bg:#F7F8FC; --c-card:#FFFFFF; --c-border:#EDEFF5; --c-line:#F1F3F9;
    --c-text:#1A1D2B; --c-muted:#7A8194; --c-faint:#B4B9C9;
    --c-indigo:#4F46E5; --c-indigo-dark:#3F37C9; --c-indigo-soft:#EEF0FF;
    --c-radius:14px; --c-gap:16px;

    background:var(--c-bg); color:var(--c-text);
    font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;
    font-size:13px; line-height:1.45;
    padding:22px 24px 32px; min-height:100%; box-sizing:border-box;
}
.cal-page *,.cal-page *::before,.cal-page *::after{box-sizing:border-box}
.cal-page h1,.cal-page h2,.cal-page h3,.cal-page h4,.cal-page p{margin:0}
.cal-page button{font:inherit}

/* ---------- header -------------------------------------------------------- */
.cal-head{display:flex;align-items:flex-start;justify-content:flex-end;gap:16px;flex-wrap:wrap;margin-bottom:16px}
.cal-headtools{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.cal-btn{border:1px solid var(--c-border);background:#fff;color:var(--c-text);font-weight:600;
    padding:9px 16px;border-radius:10px;cursor:pointer;transition:.15s;white-space:nowrap}
.cal-btn:hover{border-color:var(--c-indigo);color:var(--c-indigo)}
.cal-nav{width:36px;height:36px;display:grid;place-items:center;padding:0;border-radius:10px}
.cal-btn-primary{background:var(--c-indigo);border-color:var(--c-indigo);color:#fff;
    display:inline-flex;align-items:center;gap:7px;font-weight:700}
.cal-btn-primary:hover{background:var(--c-indigo-dark);border-color:var(--c-indigo-dark);color:#fff}

/* ---------- filter chips -------------------------------------------------- */
.cal-filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px}
.cal-chip{display:inline-flex;align-items:center;gap:7px;border:1px solid var(--c-border);
    background:#fff;color:var(--c-muted);font-weight:600;padding:8px 15px;border-radius:9px;
    cursor:pointer;transition:.15s}
.cal-chip:hover{color:var(--c-text)}
.cal-chip .dot{width:8px;height:8px;border-radius:50%;background:var(--dotc,currentColor);flex-shrink:0}
.cal-chip.is-on{color:#fff;border-color:transparent;--dotc:#fff}
.cal-chip[data-filter="all"].is-on{background:var(--c-indigo)}
.cal-chip[data-filter="school"].is-on{background:#2563EB}
.cal-chip[data-filter="exams"].is-on{background:#E1483F}
.cal-chip[data-filter="study"].is-on{background:#12A150}
.cal-chip[data-filter="practice"].is-on{background:#F7931E}
.cal-chip[data-filter="ai-tutor"].is-on{background:#6366F1}
.cal-chip[data-filter="important"].is-on{background:#F5B301}

/* ---------- month bar + view toggle --------------------------------------- */
.cal-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:12px}
.cal-bar h2{font-size:19px;font-weight:700}
.cal-views{display:inline-flex;background:#fff;border:1px solid var(--c-border);border-radius:10px;padding:4px;gap:2px}
.cal-view{border:0;background:transparent;font-weight:600;color:var(--c-muted);
    padding:7px 18px;border-radius:7px;cursor:pointer;transition:.15s}
.cal-view:hover{color:var(--c-text)}
.cal-view.is-active{background:var(--c-indigo);color:#fff}

/* ---------- layout -------------------------------------------------------- */
.cal-layout{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:var(--c-gap);align-items:start}
.cal-col{display:flex;flex-direction:column;gap:var(--c-gap);min-width:0}
.cal-card{background:var(--c-card);border:1px solid var(--c-border);border-radius:var(--c-radius);
    box-shadow:0 1px 2px rgba(20,20,50,.03)}
.cal-card-h{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px 0}
.cal-card-h h3{font-size:14.5px;font-weight:700}
.cal-card-h a{color:var(--c-indigo);font-size:12px;font-weight:600;text-decoration:none;cursor:pointer}
.cal-card-b{padding:14px 16px}

/* ---------- month grid ---------------------------------------------------- */
.cal-grid{border:1px solid var(--c-border);border-radius:var(--c-radius);background:#fff;overflow:hidden}
.cal-dow{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));background:#FBFCFE;
    border-bottom:1px solid var(--c-border)}
.cal-dow span{padding:11px 10px;font-size:12px;font-weight:700;color:var(--c-muted);text-align:left}
.cal-weeks{display:grid;grid-template-columns:repeat(7,minmax(0,1fr))}
.cal-cell{min-height:104px;border-right:1px solid var(--c-line);border-bottom:1px solid var(--c-line);
    padding:7px 7px 8px;display:flex;flex-direction:column;gap:4px;cursor:pointer;transition:background .12s}
.cal-cell:nth-child(7n){border-right:0}
.cal-cell:hover{background:#FAFBFF}
.cal-cell.is-out{background:#FCFCFE}
.cal-cell.is-out .cal-daynum{color:var(--c-faint)}
.cal-cell.is-sel{background:var(--c-indigo-soft);box-shadow:inset 0 0 0 2px var(--c-indigo)}
.cal-daynum{font-size:12.5px;font-weight:600;color:var(--c-text);width:22px;height:22px;
    display:grid;place-items:center;border-radius:50%;flex-shrink:0}
.cal-cell.is-today .cal-daynum{background:var(--c-indigo);color:#fff;font-weight:700}
.cal-ev{border-left:3px solid var(--c-indigo);border-radius:5px;padding:4px 6px;
    font-size:10.5px;font-weight:600;line-height:1.3;overflow:hidden;cursor:pointer;transition:.12s}
.cal-ev:hover{filter:brightness(.97);transform:translateX(1px)}
.cal-ev b{display:block;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cal-ev span{display:block;color:var(--c-muted);font-weight:500;font-size:9.5px;margin-top:1px}
.cal-more{font-size:9.5px;font-weight:700;color:var(--c-muted);padding-left:3px}

/* ---------- week view ----------------------------------------------------- */
.cal-weekwrap{display:grid;grid-template-columns:repeat(7,minmax(0,1fr))}
.cal-wcol{border-right:1px solid var(--c-line);min-height:340px;padding:0 0 10px}
.cal-wcol:last-child{border-right:0}
.cal-whead{padding:10px;text-align:center;border-bottom:1px solid var(--c-line);background:#FBFCFE}
.cal-whead small{display:block;font-size:11px;font-weight:700;color:var(--c-muted)}
.cal-whead b{display:inline-grid;place-items:center;width:26px;height:26px;border-radius:50%;
    font-size:13px;font-weight:700;margin-top:4px}
.cal-whead.is-today b{background:var(--c-indigo);color:#fff}
.cal-wbody{padding:8px;display:flex;flex-direction:column;gap:5px}
.cal-wbody .cal-ev{font-size:11px}

/* ---------- list view ----------------------------------------------------- */
.cal-list{padding:6px}
.cal-lrow{display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:10px;cursor:pointer}
.cal-lrow:hover{background:#F7F8FC}
.cal-ldate{width:52px;flex-shrink:0;text-align:center;border-radius:9px;padding:6px 0;background:#F5F6FB}
.cal-ldate b{display:block;font-size:16px;font-weight:700;line-height:1}
.cal-ldate small{display:block;font-size:9.5px;font-weight:700;color:var(--c-muted);letter-spacing:.4px;margin-top:2px}
.cal-lmain{min-width:0;flex:1}
.cal-lmain b{display:block;font-size:13px;font-weight:600}
.cal-lmain span{display:block;font-size:11.5px;color:var(--c-muted);margin-top:2px}
.cal-tag{font-size:10px;font-weight:700;padding:4px 10px;border-radius:99px;white-space:nowrap;flex-shrink:0}
.cal-empty{padding:34px 16px;text-align:center;color:var(--c-muted);font-weight:600}

/* ---------- right rail ---------------------------------------------------- */
.cal-up{display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid var(--c-line);cursor:pointer}
.cal-up:last-child{border-bottom:0}
.cal-up .ic{width:28px;height:28px;border-radius:8px;display:grid;place-items:center;font-size:13px;flex-shrink:0}
.cal-up b{display:block;font-size:12.5px;font-weight:600;line-height:1.35}
.cal-up span{display:block;font-size:11px;color:var(--c-muted);margin-top:2px}
.cal-field{width:100%;border:1px solid var(--c-border);border-radius:10px;padding:10px 12px;
    font:inherit;color:var(--c-text);background:#fff;margin-bottom:9px}
.cal-field:focus{outline:2px solid var(--c-indigo-soft);border-color:var(--c-indigo)}
select.cal-field{appearance:none;cursor:pointer;
    background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%237A8194' stroke-width='2' stroke-linecap='round'%3E%3Cpath d='M3 5l4 4 4-4'/%3E%3C/svg%3E") no-repeat right 12px center}
.cal-submit{width:100%;background:var(--c-indigo);color:#fff;border:0;border-radius:10px;
    padding:11px;font-weight:700;cursor:pointer}
.cal-submit:hover{background:var(--c-indigo-dark)}
.cal-sync p{font-size:11.5px;color:var(--c-muted);line-height:1.5;margin-bottom:10px}
.cal-syncicons{display:flex;gap:8px;margin-top:10px}
.cal-syncicons span{width:32px;height:32px;border:1px solid var(--c-border);border-radius:9px;
    display:grid;place-items:center;font-size:14px;background:#fff}
.cal-legend{display:flex;flex-direction:column;gap:8px}
.cal-legend div{display:flex;align-items:center;gap:9px;font-size:12px;font-weight:600;color:var(--c-muted)}
.cal-legend i{width:9px;height:9px;border-radius:50%;flex-shrink:0}

/* ---------- schedule strip ------------------------------------------------ */
.cal-tabs{display:flex;align-items:center;gap:22px;padding:0 16px;border-bottom:1px solid var(--c-line)}
.cal-tab{border:0;background:none;padding:14px 0;font-weight:700;color:var(--c-muted);
    cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px}
.cal-tab.is-active{color:var(--c-indigo);border-bottom-color:var(--c-indigo)}
.cal-tabs .when{margin-left:auto;font-size:12px;font-weight:600;color:var(--c-muted)}
.cal-srow{display:flex;align-items:center;gap:14px;padding:12px 16px;border-bottom:1px solid var(--c-line)}
.cal-srow:last-child{border-bottom:0}
.cal-stime{width:150px;flex-shrink:0;font-size:12px;font-weight:600;color:var(--c-muted)}
.cal-sic{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;font-size:12px;flex-shrink:0}
.cal-smain{flex:1;min-width:0;display:flex;gap:8px;align-items:baseline;flex-wrap:wrap}
.cal-smain b{font-size:13px;font-weight:700}
.cal-smain span{font-size:12px;color:var(--c-muted)}
.cal-del{border:0;background:none;color:var(--c-faint);cursor:pointer;font-size:14px;padding:2px 6px;border-radius:6px}
.cal-del:hover{color:#E1483F;background:#FDECEB}

/* ---------- feature strip ------------------------------------------------- */
.cal-features{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--c-gap);
    margin-top:var(--c-gap);background:#F0F2FA;border-radius:var(--c-radius);padding:20px}
.cal-feat{display:flex;gap:12px;align-items:flex-start}
.cal-feat .ic{width:36px;height:36px;border-radius:11px;background:#E4E7F7;color:var(--c-indigo);
    display:grid;place-items:center;font-size:16px;flex-shrink:0}
.cal-feat b{display:block;font-size:13px;font-weight:700;color:var(--c-indigo)}
.cal-feat span{display:block;font-size:11.5px;color:var(--c-muted);margin-top:3px;line-height:1.5}

/* ---------- modal --------------------------------------------------------- */
.cal-modal{position:fixed;inset:0;background:rgba(16,18,40,.45);z-index:800;
    display:none;align-items:center;justify-content:center;padding:20px}
.cal-modal.is-open{display:flex}
.cal-modal-box{background:#fff;border-radius:16px;width:100%;max-width:420px;padding:22px;
    box-shadow:0 24px 60px rgba(20,20,50,.28);font-size:13px}
.cal-modal-box h3{font-size:17px;font-weight:700;margin-bottom:4px}
.cal-modal-box .sub{color:var(--c-muted);font-size:12px;margin-bottom:16px}
.cal-modal-row{display:grid;grid-template-columns:1fr 1fr;gap:9px}
.cal-modal-foot{display:flex;gap:9px;margin-top:6px}
.cal-modal-foot .cal-btn{flex:1;text-align:center}
.cal-modal-foot .cal-submit{flex:1}
.cal-err{color:#E1483F;font-size:11.5px;font-weight:600;margin-bottom:9px;display:none}
.cal-err.is-on{display:block}

/* ---------- responsive ---------------------------------------------------- */
@media(max-width:1280px){
    .cal-layout{grid-template-columns:1fr}
    .cal-features{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:820px){
    .cal-page{padding:16px 14px 26px}
    .cal-cell{min-height:78px}
    .cal-ev span{display:none}
    .cal-features{grid-template-columns:1fr}
    .cal-stime{width:104px}
}
</style>
@endpush

@section('content')
<div class="cal-page" id="calPage">

    {{-- ================= HEADER =================
         Title and subtitle come from the topbar — see the page-title and
         page-sub sections at the top of this file. Same as every other page. --}}
    <div class="cal-head">
        <div class="cal-headtools">
            <button type="button" class="cal-btn" id="calToday">Today</button>
            <button type="button" class="cal-btn cal-nav" id="calPrev" aria-label="Previous">‹</button>
            <button type="button" class="cal-btn cal-nav" id="calNext" aria-label="Next">›</button>
            <button type="button" class="cal-btn cal-btn-primary" id="calAddOpen">＋ Add Event</button>
        </div>
    </div>

    {{-- ================= FILTERS ================= --}}
    <div class="cal-filters" id="calFilters">
        <button type="button" class="cal-chip is-on" data-filter="all">All</button>
        @foreach ($eventTypes as $key => $t)
            <button type="button" class="cal-chip is-on" data-filter="{{ $key }}" style="--dotc:{{ $t['color'] }}">
                <i class="dot"></i>{{ $t['label'] }}
            </button>
        @endforeach
    </div>

    {{-- ================= MONTH BAR ================= --}}
    <div class="cal-bar">
        <h2 id="calPeriod">—</h2>
        <div class="cal-views">
            <button type="button" class="cal-view is-active" data-view="month">Month</button>
            <button type="button" class="cal-view" data-view="week">Week</button>
            <button type="button" class="cal-view" data-view="list">List</button>
        </div>
    </div>

    {{-- ================= MAIN LAYOUT ================= --}}
    <div class="cal-layout">

        {{-- ---------- left: the calendar itself ---------- --}}
        <div class="cal-col">

            <div class="cal-grid">
                <div class="cal-dow" id="calDow"></div>
                <div id="calBody"></div>
            </div>

            {{-- schedule strip --}}
            <div class="cal-card">
                <div class="cal-tabs">
                    <button type="button" class="cal-tab is-active" data-strip="day">Day Schedule</button>
                    <button type="button" class="cal-tab" data-strip="all">All Events</button>
                    <span class="when" id="calStripWhen"></span>
                </div>
                <div id="calStrip"></div>
            </div>

        </div>

        {{-- ---------- right rail ---------- --}}
        <div class="cal-col">

            <div class="cal-card">
                <div class="cal-card-h">
                    <h3>Upcoming Events</h3>
                    <a id="calViewAll">View all</a>
                </div>
                <div class="cal-card-b" style="padding-top:4px" id="calUpcoming"></div>
            </div>

            <div class="cal-card">
                <div class="cal-card-h"><h3>Quick Add Event</h3></div>
                <div class="cal-card-b">
                    <form id="calQuickForm" autocomplete="off">
                        <div class="cal-err" id="calQuickErr"></div>
                        <select class="cal-field" name="type" required>
                            <option value="">Select Type</option>
                            @foreach ($eventTypes as $key => $t)
                                <option value="{{ $key }}">{{ $t['label'] }}</option>
                            @endforeach
                        </select>
                        <input class="cal-field" name="title" type="text" placeholder="Event Title" maxlength="80" required>
                        <input class="cal-field" name="date" type="date" required>
                        <input class="cal-field" name="time" type="time">
                        <button type="submit" class="cal-submit">Add Event</button>
                    </form>
                </div>
            </div>

            <div class="cal-card cal-sync">
                <div class="cal-card-h"><h3>Calendar Sync</h3></div>
                <div class="cal-card-b">
                    <p>Sync your calendar and never miss an event.</p>
                    <button type="button" class="cal-btn" style="width:100%;color:var(--c-indigo);border-color:var(--c-indigo)"
                            onclick="showToast('Calendar sync is not connected yet')">Sync Now</button>
                    <div class="cal-syncicons"><span>📅</span><span>📧</span><span>🗓️</span></div>
                </div>
            </div>

            <div class="cal-card">
                <div class="cal-card-h"><h3>Legend</h3></div>
                <div class="cal-card-b cal-legend">
                    @foreach ($eventTypes as $t)
                        <div><i style="background:{{ $t['color'] }}"></i>{{ $t['label'] }}</div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ================= FEATURE STRIP ================= --}}
    <div class="cal-features">
        <div class="cal-feat"><div class="ic">📆</div><div><b>Auto Add</b><span>Events from your courses, tests and study plans are added automatically.</span></div></div>
        <div class="cal-feat"><div class="ic">🔔</div><div><b>Smart Reminders</b><span>Get timely reminders for classes, tests and deadlines.</span></div></div>
        <div class="cal-feat"><div class="ic">🔄</div><div><b>Multi Device Sync</b><span>Access your calendar anytime, anywhere on any device.</span></div></div>
        <div class="cal-feat"><div class="ic">🎯</div><div><b>Stay Organized</b><span>Plan better, stay consistent and achieve your goals.</span></div></div>
    </div>

</div>

{{-- ================= ADD / EDIT MODAL ================= --}}
<div class="cal-modal" id="calModal">
    <div class="cal-modal-box">
        <h3 id="calModalTitle">Add Event</h3>
        <p class="sub" id="calModalSub">Fill in the details and it appears on your calendar.</p>
        <form id="calModalForm" autocomplete="off">
            <div class="cal-err" id="calModalErr"></div>
            <input type="hidden" name="id">
            <input class="cal-field" name="title" type="text" placeholder="Event title" maxlength="80" required>
            <select class="cal-field" name="type" required>
                <option value="">Select type</option>
                @foreach ($eventTypes as $key => $t)
                    <option value="{{ $key }}">{{ $t['label'] }}</option>
                @endforeach
            </select>
            <div class="cal-modal-row">
                <input class="cal-field" name="date" type="date" required>
                <input class="cal-field" name="time" type="time">
            </div>
            <input class="cal-field" name="note" type="text" placeholder="Note (optional)" maxlength="60">
            <div class="cal-modal-foot">
                <button type="button" class="cal-btn" id="calModalCancel">Cancel</button>
                <button type="button" class="cal-btn" id="calModalDelete" style="display:none;color:#E1483F;border-color:#F6C9C6">Delete</button>
                <button type="submit" class="cal-submit">Save Event</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ==========================================================================
   CALENDAR  —  self-contained. No dependencies.

   State lives in CAL.events. Seeded rows come from the PHP data block above;
   anything the user adds or deletes is persisted to localStorage so it
   survives a refresh. When you wire a real backend, replace persist() and
   the three mutation points (add / update / remove) with fetch() calls to a
   controller — nothing else in this file needs to change.
   ========================================================================== */
(function () {
    'use strict';

    var TYPES = @json($eventTypes);
    var SEED  = @json(array_values($calendarEvents));
    var STORE_ADD = 'schoolar.calendar.added.v1';
    var STORE_DEL = 'schoolar.calendar.removed.v1';

    var DOW    = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    var MONTHS = ['January','February','March','April','May','June','July',
                  'August','September','October','November','December'];
    var MON3   = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

    /* ---------- date helpers (all local time, no timezone surprises) ------- */
    function iso(d){
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }
    function parse(s){ var p = s.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); }
    function addDays(d, n){ var x = new Date(d.getTime()); x.setDate(x.getDate() + n); return x; }
    // Monday = 0 … Sunday = 6
    function dowIndex(d){ return (d.getDay() + 6) % 7; }
    function startOfWeek(d){ return addDays(d, -dowIndex(d)); }
    function fmt12(t){
        if (!t) return 'All Day';
        var p = t.split(':'), h = +p[0], m = p[1];
        var ap = h >= 12 ? 'PM' : 'AM';
        h = h % 12; if (h === 0) h = 12;
        return h + ':' + m + ' ' + ap;
    }
    function range(ev){
        if (!ev.time) return 'All Day';
        return ev.end ? fmt12(ev.time) + ' – ' + fmt12(ev.end) : fmt12(ev.time);
    }

    /* ---------- storage ---------------------------------------------------- */
    function read(key){
        try { return JSON.parse(localStorage.getItem(key)) || []; }
        catch (e) { return []; }
    }
    function write(key, val){
        try { localStorage.setItem(key, JSON.stringify(val)); } catch (e) {}
    }

    var CAL = {
        added:   read(STORE_ADD),
        removed: read(STORE_DEL),
        events:  [],
        view:    'month',
        strip:   'day',
        cursor:  new Date(),          // which month/week is on screen
        selected: iso(new Date()),    // which day the strip is showing
        filters: null                 // null = all types on
    };

    function rebuild(){
        CAL.events = SEED.concat(CAL.added)
            .filter(function (e) { return CAL.removed.indexOf(e.id) === -1; })
            .sort(function (a, b) {
                if (a.date !== b.date) return a.date < b.date ? -1 : 1;
                return (a.time || '00:00') < (b.time || '00:00') ? -1 : 1;
            });
    }

    function visible(){
        if (!CAL.filters) return CAL.events;
        return CAL.events.filter(function (e) { return CAL.filters.indexOf(e.type) !== -1; });
    }
    function onDate(d){ return visible().filter(function (e) { return e.date === d; }); }

    /* ---------- element helper -------------------------------------------- */
    function el(tag, cls, html){
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (html !== undefined) n.innerHTML = html;
        return n;
    }
    function esc(s){
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function chip(ev, withTime){
        var t = TYPES[ev.type] || TYPES.school;
        var n = el('div', 'cal-ev');
        n.style.background = t.soft;
        n.style.borderLeftColor = t.color;
        n.innerHTML = '<b>' + esc(ev.title) + '</b>' +
                      (withTime ? '<span>' + esc(ev.time ? fmt12(ev.time) : 'All Day') + '</span>' : '');
        n.addEventListener('click', function (e) { e.stopPropagation(); openModal(ev); });
        return n;
    }

    /* ---------- month view -------------------------------------------------- */
    function renderMonth(){
        var body = document.getElementById('calBody');
        body.className = 'cal-weeks';
        body.innerHTML = '';

        var first = new Date(CAL.cursor.getFullYear(), CAL.cursor.getMonth(), 1);
        var last  = new Date(CAL.cursor.getFullYear(), CAL.cursor.getMonth() + 1, 0);
        var start = startOfWeek(first);
        var today = iso(new Date());
        var month = CAL.cursor.getMonth();
        var lastKey = iso(last);

        for (var i = 0; i < 42; i++) {
            var day  = addDays(start, i);
            var key  = iso(day);
            var cell = el('div', 'cal-cell');
            if (day.getMonth() !== month) cell.classList.add('is-out');
            if (key === today)            cell.classList.add('is-today');
            if (key === CAL.selected)     cell.classList.add('is-sel');
            cell.appendChild(el('div', 'cal-daynum', String(day.getDate())));

            var evs = onDate(key);
            evs.slice(0, 3).forEach(function (ev) { cell.appendChild(chip(ev, true)); });
            if (evs.length > 3) cell.appendChild(el('div', 'cal-more', '+' + (evs.length - 3) + ' more'));

            (function (k) {
                cell.addEventListener('click', function () { CAL.selected = k; CAL.strip = 'day'; render(); });
            })(key);
            body.appendChild(cell);

            // Stop at the end of the week that contains the last day of the
            // month, so short months don't render a trailing all-grey row.
            if (dowIndex(day) === 6 && key >= lastKey) break;
        }
    }

    /* ---------- week view --------------------------------------------------- */
    function renderWeek(){
        var body = document.getElementById('calBody');
        body.className = 'cal-weekwrap';
        body.innerHTML = '';

        var start = startOfWeek(CAL.cursor);
        var today = iso(new Date());

        for (var i = 0; i < 7; i++) {
            var day = addDays(start, i), key = iso(day);
            var col = el('div', 'cal-wcol');
            var head = el('div', 'cal-whead' + (key === today ? ' is-today' : ''));
            head.innerHTML = '<small>' + DOW[i] + '</small><b>' + day.getDate() + '</b>';
            col.appendChild(head);

            var bodyCol = el('div', 'cal-wbody');
            var evs = onDate(key);
            if (!evs.length) bodyCol.appendChild(el('div', 'cal-more', 'No events'));
            evs.forEach(function (ev) { bodyCol.appendChild(chip(ev, true)); });
            col.appendChild(bodyCol);

            (function (k) {
                col.addEventListener('click', function () { CAL.selected = k; CAL.strip = 'day'; render(); });
            })(key);
            body.appendChild(col);
        }
    }

    /* ---------- list view --------------------------------------------------- */
    function renderList(){
        var body = document.getElementById('calBody');
        body.className = 'cal-list';
        body.innerHTML = '';

        var y = CAL.cursor.getFullYear(), m = CAL.cursor.getMonth();
        var rows = visible().filter(function (e) {
            var d = parse(e.date);
            return d.getFullYear() === y && d.getMonth() === m;
        });

        if (!rows.length) {
            body.appendChild(el('div', 'cal-empty', 'No events this month for the selected filters.'));
            return;
        }

        rows.forEach(function (ev) {
            var t = TYPES[ev.type] || TYPES.school, d = parse(ev.date);
            var row = el('div', 'cal-lrow');
            row.innerHTML =
                '<div class="cal-ldate"><b>' + d.getDate() + '</b><small>' + MON3[d.getMonth()] + '</small></div>' +
                '<div class="cal-lmain"><b>' + esc(ev.title) + '</b><span>' + esc(range(ev)) +
                (ev.note ? ' · ' + esc(ev.note) : '') + '</span></div>' +
                '<span class="cal-tag" style="background:' + t.soft + ';color:' + t.color + '">' + t.label + '</span>';
            row.addEventListener('click', function () { openModal(ev); });
            body.appendChild(row);
        });
    }

    /* ---------- right rail: upcoming --------------------------------------- */
    function renderUpcoming(){
        var box = document.getElementById('calUpcoming');
        box.innerHTML = '';
        var today = iso(new Date());
        var next = visible().filter(function (e) { return e.date >= today; }).slice(0, 5);

        if (!next.length) { box.appendChild(el('div', 'cal-more', 'Nothing coming up.')); return; }

        next.forEach(function (ev) {
            var t = TYPES[ev.type] || TYPES.school, d = parse(ev.date);
            var row = el('div', 'cal-up');
            row.innerHTML =
                '<div class="ic" style="background:' + t.soft + '">' + t.icon + '</div>' +
                '<div><b>' + esc(ev.title) + '</b><span>' + d.getDate() + ' ' +
                MONTHS[d.getMonth()].slice(0, 3) + ' ' + d.getFullYear() + ', ' + esc(range(ev)) + '</span></div>';
            row.addEventListener('click', function () {
                CAL.cursor = parse(ev.date); CAL.selected = ev.date; CAL.strip = 'day'; render();
            });
            box.appendChild(row);
        });
    }

    /* ---------- schedule strip --------------------------------------------- */
    function renderStrip(){
        var box = document.getElementById('calStrip');
        var when = document.getElementById('calStripWhen');
        box.innerHTML = '';

        var rows, sel = parse(CAL.selected);
        if (CAL.strip === 'day') {
            rows = onDate(CAL.selected);
            when.textContent = sel.getDate() + ' ' + MONTHS[sel.getMonth()] + ' ' + sel.getFullYear();
        } else {
            var y = CAL.cursor.getFullYear(), m = CAL.cursor.getMonth();
            rows = visible().filter(function (e) {
                var d = parse(e.date);
                return d.getFullYear() === y && d.getMonth() === m;
            });
            when.textContent = rows.length + ' event' + (rows.length === 1 ? '' : 's') +
                               ' in ' + MONTHS[m] + ' ' + y;
        }

        if (!rows.length) {
            box.appendChild(el('div', 'cal-empty',
                CAL.strip === 'day' ? 'Nothing scheduled for this day.' : 'No events this month.'));
            return;
        }

        rows.forEach(function (ev) {
            var t = TYPES[ev.type] || TYPES.school;
            var d = parse(ev.date);
            var row = el('div', 'cal-srow');
            var left = CAL.strip === 'day'
                ? range(ev)
                : d.getDate() + ' ' + MON3[d.getMonth()] + ' · ' + (ev.time ? fmt12(ev.time) : 'All Day');
            row.innerHTML =
                '<div class="cal-stime">' + esc(left) + '</div>' +
                '<div class="cal-sic" style="background:' + t.soft + '">' + t.icon + '</div>' +
                '<div class="cal-smain"><b>' + esc(ev.title) + '</b><span>' + esc(ev.note || '') + '</span></div>' +
                '<span class="cal-tag" style="background:' + t.soft + ';color:' + t.color + '">' + t.label + '</span>';

            var del = el('button', 'cal-del', '✕');
            del.type = 'button';
            del.title = 'Delete event';
            del.addEventListener('click', function (e) { e.stopPropagation(); removeEvent(ev.id); });
            row.appendChild(del);
            row.addEventListener('click', function () { openModal(ev); });
            box.appendChild(row);
        });
    }

    /* ---------- master render ---------------------------------------------- */
    function render(){
        rebuild();

        var dow = document.getElementById('calDow');
        dow.style.display = CAL.view === 'month' ? 'grid' : 'none';
        if (!dow.childNodes.length) {
            DOW.forEach(function (d) { dow.appendChild(el('span', null, d)); });
        }

        var label;
        if (CAL.view === 'week') {
            var s = startOfWeek(CAL.cursor), e = addDays(s, 6);
            label = s.getDate() + ' ' + MON3[s.getMonth()] + ' – ' +
                    e.getDate() + ' ' + MON3[e.getMonth()] + ' ' + e.getFullYear();
        } else {
            label = MONTHS[CAL.cursor.getMonth()] + ' ' + CAL.cursor.getFullYear();
        }
        document.getElementById('calPeriod').textContent = label;

        if (CAL.view === 'month') renderMonth();
        else if (CAL.view === 'week') renderWeek();
        else renderList();

        renderUpcoming();
        renderStrip();

        document.querySelectorAll('.cal-tab').forEach(function (b) {
            b.classList.toggle('is-active', b.dataset.strip === CAL.strip);
        });
        document.querySelectorAll('.cal-view').forEach(function (b) {
            b.classList.toggle('is-active', b.dataset.view === CAL.view);
        });
    }

    /* ---------- mutations --------------------------------------------------- */
    function addEvent(data){
        data.id = 'u' + Date.now() + Math.floor(Math.random() * 1000);
        CAL.added.push(data);
        write(STORE_ADD, CAL.added);
        CAL.cursor = parse(data.date);
        CAL.selected = data.date;
        CAL.strip = 'day';
        render();
        if (window.showToast) showToast('“' + data.title + '” added');
    }
    function updateEvent(data){
        var i = -1;
        CAL.added.forEach(function (e, ix) { if (e.id === data.id) i = ix; });
        if (i > -1) {
            CAL.added[i] = data;
        } else {
            // Editing a seeded row: hide the original and store the edited copy.
            CAL.removed.push(data.id);
            data.id = 'u' + Date.now();
            CAL.added.push(data);
            write(STORE_DEL, CAL.removed);
        }
        write(STORE_ADD, CAL.added);
        CAL.cursor = parse(data.date);
        CAL.selected = data.date;
        render();
        if (window.showToast) showToast('Event updated');
    }
    function removeEvent(id){
        CAL.added = CAL.added.filter(function (e) { return e.id !== id; });
        if (CAL.removed.indexOf(id) === -1) CAL.removed.push(id);
        write(STORE_ADD, CAL.added);
        write(STORE_DEL, CAL.removed);
        render();
        if (window.showToast) showToast('Event deleted');
    }

    /* ---------- modal ------------------------------------------------------- */
    var modal = document.getElementById('calModal');
    var mForm = document.getElementById('calModalForm');
    var mDel  = document.getElementById('calModalDelete');

    // NOTE: always go through form.elements. `form.id` and `form.title` resolve
    // to the HTMLElement's own id/title properties, NOT to the named inputs.
    var mf = mForm.elements;

    function openModal(ev){
        mForm.reset();
        document.getElementById('calModalErr').classList.remove('is-on');
        if (ev) {
            document.getElementById('calModalTitle').textContent = 'Edit Event';
            document.getElementById('calModalSub').textContent = 'Change the details or delete this event.';
            mf.id.value    = ev.id;
            mf.title.value = ev.title;
            mf.type.value  = ev.type;
            mf.date.value  = ev.date;
            mf.time.value  = ev.time || '';
            mf.note.value  = ev.note || '';
            mDel.style.display = '';
        } else {
            document.getElementById('calModalTitle').textContent = 'Add Event';
            document.getElementById('calModalSub').textContent = 'Fill in the details and it appears on your calendar.';
            mf.id.value = '';
            mf.date.value = CAL.selected;
            mDel.style.display = 'none';
        }
        modal.classList.add('is-open');
        setTimeout(function () { mf.title.focus(); }, 30);
    }
    function closeModal(){ modal.classList.remove('is-open'); }

    mForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var err = document.getElementById('calModalErr');
        var data = {
            id:    mf.id.value,
            title: mf.title.value.trim(),
            type:  mf.type.value,
            date:  mf.date.value,
            time:  mf.time.value || null,
            end:   null,
            note:  mf.note.value.trim()
        };
        if (!data.title || !data.type || !data.date) {
            err.textContent = 'Title, type and date are all required.';
            err.classList.add('is-on');
            return;
        }
        if (data.id) updateEvent(data); else addEvent(data);
        closeModal();
    });
    mDel.addEventListener('click', function () {
        if (mf.id.value) removeEvent(mf.id.value);
        closeModal();
    });
    document.getElementById('calModalCancel').addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });

    /* ---------- quick add --------------------------------------------------- */
    var qForm = document.getElementById('calQuickForm');
    var qf = qForm.elements;
    qf.date.value = iso(new Date());
    qForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var err = document.getElementById('calQuickErr');
        var data = {
            title: qf.title.value.trim(),
            type:  qf.type.value,
            date:  qf.date.value,
            time:  qf.time.value || null,
            end:   null,
            note:  ''
        };
        if (!data.title || !data.type || !data.date) {
            err.textContent = 'Pick a type, a title and a date.';
            err.classList.add('is-on');
            return;
        }
        err.classList.remove('is-on');
        addEvent(data);
        qForm.reset();
        qf.date.value = data.date;
    });

    /* ---------- toolbar wiring ---------------------------------------------- */
    function step(dir){
        if (CAL.view === 'week') {
            CAL.cursor = addDays(CAL.cursor, dir * 7);
        } else {
            CAL.cursor = new Date(CAL.cursor.getFullYear(), CAL.cursor.getMonth() + dir, 1);
        }
        render();
    }
    document.getElementById('calPrev').addEventListener('click', function () { step(-1); });
    document.getElementById('calNext').addEventListener('click', function () { step(1); });
    document.getElementById('calToday').addEventListener('click', function () {
        CAL.cursor = new Date();
        CAL.selected = iso(new Date());
        CAL.strip = 'day';
        render();
    });
    document.getElementById('calAddOpen').addEventListener('click', function () { openModal(null); });
    document.getElementById('calViewAll').addEventListener('click', function () {
        CAL.strip = 'all';
        render();
        document.getElementById('calStrip').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    document.querySelectorAll('.cal-view').forEach(function (b) {
        b.addEventListener('click', function () { CAL.view = b.dataset.view; render(); });
    });
    document.querySelectorAll('.cal-tab').forEach(function (b) {
        b.addEventListener('click', function () { CAL.strip = b.dataset.strip; render(); });
    });

    /* ---------- filters ------------------------------------------------------ */
    var allTypes = Object.keys(TYPES);
    document.getElementById('calFilters').addEventListener('click', function (e) {
        var btn = e.target.closest('.cal-chip');
        if (!btn) return;
        var f = btn.dataset.filter;
        var chips = document.querySelectorAll('.cal-chip[data-filter]:not([data-filter="all"])');

        if (f === 'all') {
            CAL.filters = null;
        } else {
            var on = CAL.filters ? CAL.filters.slice() : allTypes.slice();
            var i = on.indexOf(f);
            if (i > -1) on.splice(i, 1); else on.push(f);
            CAL.filters = on.length === allTypes.length ? null : on;
        }

        var active = CAL.filters || allTypes;
        chips.forEach(function (c) { c.classList.toggle('is-on', active.indexOf(c.dataset.filter) > -1); });
        document.querySelector('.cal-chip[data-filter="all"]').classList.toggle('is-on', CAL.filters === null);
        render();
    });

    /* ---------- keyboard ----------------------------------------------------- */
    document.addEventListener('keydown', function (e) {
        if (modal.classList.contains('is-open')) return;
        if (/^(INPUT|SELECT|TEXTAREA)$/.test(document.activeElement.tagName)) return;
        if (e.key === 'ArrowLeft')  step(-1);
        if (e.key === 'ArrowRight') step(1);
    });

    render();
})();
</script>
@endpush
