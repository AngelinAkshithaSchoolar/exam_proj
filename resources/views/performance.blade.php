@extends('layouts.app')

@section('title', 'Performance — schoolar.ai')

{{-- These two feed the topbar. Without them the layout falls back to its
     default heading ("Dashboard"), which is what this page used to show. --}}
@section('page-title', 'Performance')
@section('page-sub', 'Track your academic and exam performance in one place.')

@php
    /*
    |--------------------------------------------------------------------------
    | PAGE DATA
    |--------------------------------------------------------------------------
    | Everything the page renders lives here. When you wire a controller up,
    | delete this block and pass the same variable names from the controller:
    |   return view('performance', compact('schoolScore', 'jeeScore', ...));
    | The markup below will not need to change.
    */

    $classOptions   = ['Class 12 CBSE + JEE Main', 'Class 12 CBSE', 'JEE Main'];
    $selectedClass  = 'Class 12 CBSE + JEE Main';
    $dateRange      = '24 May – 24 May 2024';

    // --- Headline scores -----------------------------------------------------
    $schoolScore    = 86;
    $schoolRank     = 'Top 8%';
    $schoolDelta    = '6% vs last term';

    $jeeScore       = 78;
    $jeePercentile  = '94.2';
    $jeeDelta       = '8 percentile vs last 30 days';

    // --- Study overview ------------------------------------------------------
    $studyOverview = [
        ['icon' => 'calendar', 'tone' => 'blue',  'value' => '248',      'label' => 'Days Left'],
        ['icon' => 'clock',    'tone' => 'amber', 'value' => '128h 30m', 'label' => 'Hours Studied'],
        ['icon' => 'check',    'tone' => 'green', 'value' => '62%',      'label' => 'Syllabus Completed'],
    ];

    // --- Performance over time (19 points, every 3rd is labelled) ------------
    $trendLabels = ['24 Apr','','','29 Apr','','','4 May','','','9 May','','','14 May','','','19 May','','','24 May'];
    $trendSchool = [52,56,60,62,63,65,66,67,68,70,71,72,73,75,77,79,81,84,86];
    $trendJee    = [34,36,40,44,48,50,52,55,57,54,58,60,63,61,65,68,70,74,78];

    // --- School subject performance -----------------------------------------
    $schoolSubjects = [
        ['name' => 'Mathematics', 'icon' => '√x', 'tone' => 'green',  'score' => 91, 'avg' => 82, 'trend' => 'up'],
        ['name' => 'Physics',     'icon' => '⚛',  'tone' => 'blue',   'score' => 86, 'avg' => 79, 'trend' => 'up'],
        ['name' => 'Chemistry',   'icon' => '⚗',  'tone' => 'amber',  'score' => 84, 'avg' => 81, 'trend' => 'flat'],
        ['name' => 'English',     'icon' => '✎',  'tone' => 'blue',   'score' => 92, 'avg' => 85, 'trend' => 'up'],
        ['name' => 'Biology',     'icon' => '❁',  'tone' => 'green',  'score' => 78, 'avg' => 84, 'trend' => 'down'],
    ];

    // --- JEE readiness -------------------------------------------------------
    $jeeReadiness = [
        ['name' => 'Physics',     'icon' => '⚛', 'tone' => 'blue',  'readiness' => 82, 'accuracy' => '82%'],
        ['name' => 'Chemistry',   'icon' => '⚗', 'tone' => 'amber', 'readiness' => 74, 'accuracy' => '74%'],
        ['name' => 'Mathematics', 'icon' => '√x','tone' => 'green', 'readiness' => 61, 'accuracy' => '61%'],
    ];
    $jeePrediction = [
        ['label' => 'Predicted Score',      'value' => '187 / 300'],
        ['label' => 'Predicted Percentile', 'value' => '94.2'],
        ['label' => 'Target Percentile',    'value' => '98+'],
    ];

    // --- Upcoming dates ------------------------------------------------------
    $upcomingDates = [
        ['day' => '27', 'month' => 'MAY', 'title' => 'JEE Main Mock Test',          'type' => 'Full Length Test', 'time' => '10:00 AM'],
        ['day' => '05', 'month' => 'JUN', 'title' => 'Physics Unit Test',            'type' => 'School Exam',      'time' => '11:30 AM'],
        ['day' => '20', 'month' => 'JUN', 'title' => 'JEE Main Full Syllabus Test',  'type' => 'Mock Test',        'time' => '09:00 AM'],
    ];

    // --- Actual exam result --------------------------------------------------
    $actualExam = [
        'session'    => 'JEE Main 2025 (Session 1)',
        'score'      => '214', 'scoreOutOf' => '/ 300',
        'percentile' => '98.14',
        'rank'       => '27,845',
        'rows'       => [
            ['metric' => 'Score',      'predicted' => '205 – 220',   'actual' => '214',   'diff' => '+9',     'good' => true],
            ['metric' => 'Percentile', 'predicted' => '97.8 – 98.4', 'actual' => '98.14', 'diff' => '+0.34',  'good' => true],
            ['metric' => 'Rank (AIR)', 'predicted' => '24k – 35k',   'actual' => '27,845','diff' => 'Better', 'good' => true],
        ],
    ];

    // --- Academic health -----------------------------------------------------
    $academicHealth = [
        ['label' => 'Strong',          'tone' => 'green', 'items' => ['Mathematics', 'English']],
        ['label' => 'Needs Attention', 'tone' => 'amber', 'items' => ['Chemistry']],
        ['label' => 'At Risk',         'tone' => 'red',   'items' => ['Biology']],
    ];

    // --- Test performance (donut) -------------------------------------------
    $testPerformance = [
        ['label' => 'Full Length Mock Tests', 'value' => 78, 'color' => '#2563EB'],
        ['label' => 'Chapter Tests',          'value' => 72, 'color' => '#10B981'],
        ['label' => 'Sectional Tests',        'value' => 74, 'color' => '#F59E0B'],
        ['label' => 'Practice Tests',         'value' => 68, 'color' => '#4F46E5'],
        ['label' => 'Other Assessments',      'value' => 60, 'color' => '#D1D5DB'],
    ];
    $testAverage = 78;

    // --- Skills overview -----------------------------------------------------
    $skills = [
        ['label' => 'Conceptual Understanding', 'value' => 82],
        ['label' => 'Problem Solving',          'value' => 76],
        ['label' => 'Speed',                    'value' => 68],
        ['label' => 'Accuracy',                 'value' => 72],
        ['label' => 'Consistency',              'value' => 70],
    ];

    $recommendation = 'Increase practice in Mathematics (Calculus, Coordinate Geometry) and attempt 2 more full length tests this month to reach your target percentile.';
@endphp

@push('styles')
<style>
/* ==========================================================================
   PERFORMANCE PAGE  —  scoped to .perf-page so nothing here can leak into
   the shared layout / sidebar.
   ========================================================================== */
.perf-page{
    --pf-bg:#F7F8FC;
    --pf-card:#FFFFFF;
    --pf-border:#EDEFF5;
    --pf-text:#1A1D2B;
    --pf-muted:#7A8194;
    --pf-indigo:#4F46E5;
    --pf-indigo-soft:#EEF0FF;
    --pf-blue:#2563EB;
    --pf-blue-soft:#EAF1FE;
    --pf-purple:#7C3AED;
    --pf-green:#12A150;
    --pf-green-soft:#E7F7EF;
    --pf-amber:#E28C13;
    --pf-amber-soft:#FDF3E3;
    --pf-red:#E1483F;
    --pf-red-soft:#FDECEB;
    --pf-radius:14px;
    --pf-gap:16px;

    background:var(--pf-bg);
    color:var(--pf-text);
    font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;
    font-size:13px;
    line-height:1.45;
    padding:22px 24px 32px;
    min-height:100%;
    box-sizing:border-box;
}
.perf-page *,.perf-page *::before,.perf-page *::after{box-sizing:border-box;}
.perf-page h1,.perf-page h2,.perf-page h3,.perf-page h4,.perf-page p{margin:0;}

/* ---------- page header --------------------------------------------------- */
.pf-head{display:flex;align-items:flex-start;justify-content:flex-end;gap:16px;flex-wrap:wrap;margin-bottom:18px;}
.pf-select{
    appearance:none;background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%237A8194' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 5l4 4 4-4'/%3E%3C/svg%3E") no-repeat right 12px center;
    border:1px solid var(--pf-border);border-radius:10px;padding:10px 34px 10px 14px;
    font:inherit;font-weight:600;color:var(--pf-text);cursor:pointer;min-width:210px;
}
.pf-select:focus{outline:2px solid var(--pf-indigo-soft);border-color:var(--pf-indigo);}

/* ---------- tab bar + date range ------------------------------------------ */
.pf-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;}
.pf-tabs{display:inline-flex;background:#fff;border:1px solid var(--pf-border);border-radius:10px;padding:4px;gap:2px;}
.pf-tab{
    border:0;background:transparent;font:inherit;font-weight:600;color:var(--pf-muted);
    padding:8px 18px;border-radius:7px;cursor:pointer;white-space:nowrap;transition:.15s;
}
.pf-tab:hover{color:var(--pf-text);}
.pf-tab.is-active{background:var(--pf-indigo);color:#fff;}
.pf-daterange{
    display:inline-flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--pf-border);
    border-radius:10px;padding:9px 14px;font-weight:600;color:var(--pf-text);
}
.pf-daterange svg{color:var(--pf-muted);flex-shrink:0;}

/* ---------- layout grid --------------------------------------------------- */
.pf-grid{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:var(--pf-gap);align-items:start;}
.pf-col{display:flex;flex-direction:column;gap:var(--pf-gap);min-width:0;}
.pf-row2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:var(--pf-gap);}
.pf-bottom{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:var(--pf-gap);margin-top:var(--pf-gap);}

/* ---------- card ---------------------------------------------------------- */
.pf-card{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:var(--pf-radius);padding:18px;min-width:0;}
.pf-card-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px;}
.pf-card-title{font-size:14px;font-weight:700;}
.pf-link{color:var(--pf-indigo);font-weight:600;font-size:12px;text-decoration:none;}
.pf-link:hover{text-decoration:underline;}

/* ---------- headline score cards ------------------------------------------ */
.pf-score{background:#F4F6FE;border:1px solid #E8ECFB;border-radius:var(--pf-radius);padding:22px;}
.pf-score.is-exam{background:#F3F1FE;border-color:#E9E5FD;}
.pf-score-icon{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;background:#E2E9FC;color:var(--pf-blue);margin-bottom:14px;}
.pf-score.is-exam .pf-score-icon{background:#E7E1FD;color:var(--pf-purple);}
.pf-score-label{font-size:13px;font-weight:700;color:var(--pf-blue);}
.pf-score.is-exam .pf-score-label{color:var(--pf-purple);}
.pf-score-value{font-size:38px;font-weight:800;letter-spacing:-1px;line-height:1.1;margin:2px 0 10px;}
.pf-score-sub{font-size:12px;color:var(--pf-muted);}
.pf-score-sub b{color:var(--pf-text);font-weight:700;}
.pf-delta{display:inline-flex;align-items:center;gap:4px;color:var(--pf-green);font-weight:600;font-size:12px;margin-top:8px;}

/* ---------- study overview ------------------------------------------------ */
.pf-stat{display:flex;align-items:center;gap:12px;padding:11px 0;}
.pf-stat + .pf-stat{border-top:1px solid var(--pf-border);}
.pf-stat-icon{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;}
.pf-stat-icon.blue{background:var(--pf-blue-soft);color:var(--pf-blue);}
.pf-stat-icon.amber{background:var(--pf-amber-soft);color:var(--pf-amber);}
.pf-stat-icon.green{background:var(--pf-green-soft);color:var(--pf-green);}
.pf-stat-value{font-size:16px;font-weight:700;}
.pf-stat-label{font-size:11.5px;color:var(--pf-muted);}

/* ---------- chart --------------------------------------------------------- */
.pf-chart-tabs{display:inline-flex;gap:4px;}
.pf-chip{border:0;background:transparent;font:inherit;font-size:12px;font-weight:600;color:var(--pf-muted);padding:5px 11px;border-radius:7px;cursor:pointer;}
.pf-chip.is-active{background:var(--pf-indigo-soft);color:var(--pf-indigo);}
.pf-legend{display:flex;gap:16px;margin-bottom:6px;font-size:12px;color:var(--pf-muted);font-weight:600;}
.pf-legend span{display:inline-flex;align-items:center;gap:6px;}
.pf-legend i{width:16px;height:2px;border-radius:2px;display:inline-block;position:relative;}
.pf-legend i::after{content:'';position:absolute;inset:-3px 0 0 5px;width:6px;height:6px;border-radius:50%;background:inherit;}
.pf-chart-box{position:relative;height:250px;width:100%;}
.pf-donut-box{position:relative;height:190px;width:190px;flex-shrink:0;margin:auto;}
.pf-donut-center{position:absolute;inset:0;display:grid;place-content:center;text-align:center;pointer-events:none;}
.pf-donut-center b{display:block;font-size:26px;font-weight:800;letter-spacing:-.5px;}
.pf-donut-center small{color:var(--pf-muted);font-size:10.5px;line-height:1.3;display:block;}

/* ---------- tables -------------------------------------------------------- */
.pf-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
.pf-table{width:100%;border-collapse:collapse;font-size:12.5px;min-width:280px;}
.pf-table th{
    text-align:left;font-weight:600;color:var(--pf-muted);font-size:11.5px;
    padding:0 0 9px;border-bottom:1px solid var(--pf-border);white-space:nowrap;
}
.pf-table td{padding:11px 0;border-bottom:1px solid #F4F5F9;vertical-align:middle;}
.pf-table tr:last-child td{border-bottom:0;}
.pf-table th:not(:first-child),.pf-table td:not(:first-child){text-align:right;padding-left:10px;}
.pf-subject{display:flex;align-items:center;gap:9px;font-weight:600;white-space:nowrap;}
.pf-subject-icon{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;font-size:12px;flex-shrink:0;}
.pf-subject-icon.green{background:var(--pf-green-soft);color:var(--pf-green);}
.pf-subject-icon.blue{background:var(--pf-blue-soft);color:var(--pf-blue);}
.pf-subject-icon.amber{background:var(--pf-amber-soft);color:var(--pf-amber);}
.pf-trend{font-size:14px;font-weight:700;line-height:1;}
.pf-trend.up{color:var(--pf-green);}
.pf-trend.down{color:var(--pf-red);}
.pf-trend.flat{color:var(--pf-muted);}
.pf-bar{width:88px;height:6px;border-radius:4px;background:#EDEFF5;overflow:hidden;display:inline-block;vertical-align:middle;}
.pf-bar i{display:block;height:100%;border-radius:4px;background:var(--pf-indigo);}
.pf-cellbar{display:flex;align-items:center;justify-content:flex-end;gap:10px;}

/* ---------- prediction box ------------------------------------------------ */
.pf-predict{background:#F7F8FC;border-radius:10px;padding:12px 14px;margin-top:14px;}
.pf-predict div{display:flex;justify-content:space-between;gap:12px;padding:5px 0;font-size:12.5px;}
.pf-predict div span:first-child{color:var(--pf-muted);}
.pf-predict div span:last-child{font-weight:700;}

/* ---------- upcoming dates ------------------------------------------------ */
.pf-event{display:flex;gap:12px;padding:11px 0;}
.pf-event + .pf-event{border-top:1px solid var(--pf-border);}
.pf-event-date{
    width:46px;flex-shrink:0;border-radius:10px;background:var(--pf-red-soft);color:var(--pf-red);
    text-align:center;padding:6px 0;line-height:1.15;
}
.pf-event-date b{display:block;font-size:17px;font-weight:800;}
.pf-event-date small{font-size:9.5px;font-weight:700;letter-spacing:.5px;}
.pf-event-title{font-weight:700;font-size:12.5px;}
.pf-event-meta{color:var(--pf-muted);font-size:11.5px;margin-top:2px;}
.pf-event-time{color:var(--pf-muted);font-size:11px;display:inline-flex;align-items:center;gap:4px;margin-top:3px;}

/* ---------- AI coach ------------------------------------------------------ */
.pf-coach{background:#F3F4FF;border:1px solid #E6E8FD;border-radius:var(--pf-radius);padding:16px;}
.pf-coach-head{display:flex;align-items:center;gap:9px;margin-bottom:9px;}
.pf-coach-avatar{width:30px;height:30px;border-radius:9px;background:#E0E3FB;display:grid;place-items:center;font-size:15px;}
.pf-coach h3{font-size:13px;font-weight:700;color:var(--pf-indigo);}
.pf-coach p{font-size:12px;color:#4A5063;line-height:1.55;}
.pf-coach p b{color:var(--pf-text);}
.pf-btn{
    display:inline-block;width:100%;text-align:center;border:1px solid var(--pf-indigo);
    background:#fff;color:var(--pf-indigo);font:inherit;font-weight:600;font-size:12px;
    padding:9px 14px;border-radius:9px;cursor:pointer;margin-top:12px;text-decoration:none;
}
.pf-btn:hover{background:var(--pf-indigo);color:#fff;}
.pf-btn-solid{background:var(--pf-indigo);color:#fff;width:auto;white-space:nowrap;}
.pf-btn-solid:hover{background:#4338CA;color:#fff;}

/* ---------- actual exam result -------------------------------------------- */
.pf-result-session{color:var(--pf-muted);font-size:11.5px;margin-bottom:10px;}
.pf-result-nums{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;text-align:center;margin-bottom:14px;}
.pf-result-nums b{display:block;font-size:17px;font-weight:800;letter-spacing:-.4px;}
.pf-result-nums b span{font-size:11px;font-weight:600;color:var(--pf-muted);}
.pf-result-nums small{color:var(--pf-muted);font-size:10.5px;}
.pf-good{color:var(--pf-green);font-weight:700;}

/* ---------- academic health ----------------------------------------------- */
.pf-health{display:grid;grid-template-columns:repeat(3,minmax(0,1fr)) 200px;gap:12px;align-items:stretch;}
.pf-health-box{border-radius:12px;padding:13px 14px;border:1px solid transparent;}
.pf-health-box.green{background:var(--pf-green-soft);border-color:#CFEEDF;}
.pf-health-box.amber{background:var(--pf-amber-soft);border-color:#F6E3C4;}
.pf-health-box.red{background:var(--pf-red-soft);border-color:#F8D6D3;}
.pf-health-label{font-size:12px;font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:6px;}
.pf-health-box.green .pf-health-label{color:var(--pf-green);}
.pf-health-box.amber .pf-health-label{color:var(--pf-amber);}
.pf-health-box.red .pf-health-label{color:var(--pf-red);}
.pf-dot{width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0;}
.pf-health-item{display:flex;align-items:center;gap:7px;font-size:12px;font-weight:600;padding:3px 0;color:#3B4152;}
.pf-health-note{text-align:center;display:flex;flex-direction:column;justify-content:center;gap:4px;padding:8px;}
.pf-health-note .pf-trophy{font-size:26px;}
.pf-health-note b{font-size:12.5px;}
.pf-health-note small{color:var(--pf-muted);font-size:11px;line-height:1.45;}

/* ---------- test performance legend --------------------------------------- */
.pf-donut-wrap{display:flex;align-items:center;gap:20px;flex-wrap:wrap;}
.pf-donut-legend{flex:1;min-width:180px;display:flex;flex-direction:column;gap:11px;}
.pf-donut-legend div{display:flex;align-items:center;gap:9px;font-size:12.5px;}
.pf-donut-legend div span:first-of-type{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
.pf-donut-legend div span:nth-of-type(2){flex:1;color:#3B4152;}
.pf-donut-legend div b{font-weight:700;}

/* ---------- skills -------------------------------------------------------- */
.pf-skill{display:flex;align-items:center;gap:12px;padding:8px 0;font-size:12.5px;}
.pf-skill span:first-child{flex:1;color:#3B4152;min-width:0;}
.pf-skill .pf-bar{width:120px;flex-shrink:0;}
.pf-skill b{width:34px;text-align:right;font-weight:700;flex-shrink:0;}

/* ---------- recommendation banner ----------------------------------------- */
.pf-reco{
    display:flex;align-items:center;gap:14px;background:#F3F4FF;border:1px solid #E6E8FD;
    border-radius:var(--pf-radius);padding:16px 18px;margin-top:var(--pf-gap);flex-wrap:wrap;
}
.pf-reco-icon{width:38px;height:38px;border-radius:11px;background:#E0E3FB;display:grid;place-items:center;font-size:18px;flex-shrink:0;}
.pf-reco-body{flex:1;min-width:220px;}
.pf-reco-body h3{font-size:13px;font-weight:700;color:var(--pf-indigo);margin-bottom:3px;}
.pf-reco-body p{font-size:12px;color:#4A5063;line-height:1.5;}

/* ---------- tab filtering -------------------------------------------------- */
.perf-page [data-view].is-hidden{display:none !important;}

/* ==========================================================================
   RESPONSIVE
   ========================================================================== */
@media (max-width:1180px){
    .pf-grid{grid-template-columns:1fr;}
    .pf-health{grid-template-columns:repeat(3,minmax(0,1fr));}
    .pf-health-note{grid-column:1 / -1;flex-direction:row;justify-content:center;gap:12px;}
}
@media (max-width:860px){
    .perf-page{padding:18px 16px 28px;}
    .pf-row2,.pf-bottom{grid-template-columns:1fr;}
    .pf-health{grid-template-columns:1fr;}
    .pf-health-note{flex-direction:column;}
    .pf-donut-wrap{justify-content:center;}
}
@media (max-width:560px){
    .pf-select,.pf-daterange{width:100%;}
    .pf-tabs{width:100%;}
    .pf-tab{flex:1;padding:8px 6px;font-size:12px;}
    .pf-score-value{font-size:32px;}
    .pf-result-nums{grid-template-columns:1fr;text-align:left;}
    .pf-skill .pf-bar{width:70px;}
}
</style>
@endpush

@section('content')
<div class="perf-page">

    {{-- ================= HEADER =================
         The title and subtitle now come from the topbar — see the page-title
         and page-sub sections at the top of this file. Only the class selector
         is left here, so .pf-head right-aligns its single child. --}}
    <div class="pf-head">
        <select class="pf-select" id="pfClassSelect">
            @foreach ($classOptions as $option)
                <option @selected($option === $selectedClass)>{{ $option }}</option>
            @endforeach
        </select>
    </div>

    {{-- ================= TABS + DATE RANGE ================= --}}
    <div class="pf-toolbar">
        <div class="pf-tabs" role="tablist">
            <button type="button" class="pf-tab is-active" data-tab="combined" role="tab">Combined</button>
            <button type="button" class="pf-tab" data-tab="school" role="tab">School</button>
            <button type="button" class="pf-tab" data-tab="exam" role="tab">Exam (JEE Main)</button>
        </div>
        <div class="pf-daterange">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            <span>{{ $dateRange }}</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M9 13l2 2 4-4"/></svg>
        </div>
    </div>

    {{-- ================= MAIN GRID ================= --}}
    <div class="pf-grid">

        {{-- ---------- LEFT COLUMN ---------- --}}
        <div class="pf-col">

            {{-- headline score cards --}}
            <div class="pf-row2">
                <div class="pf-score" data-view="school">
                    <div class="pf-score-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1 2.7 2.5 6 2.5s6-1.5 6-2.5v-5"/></svg>
                    </div>
                    <div class="pf-score-label">School Performance</div>
                    <div class="pf-score-value">{{ $schoolScore }}%</div>
                    <div class="pf-score-sub">Class Rank: <b>{{ $schoolRank }}</b></div>
                    <div class="pf-delta">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        {{ $schoolDelta }}
                    </div>
                </div>

                <div class="pf-score is-exam" data-view="exam">
                    <div class="pf-score-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4" fill="currentColor"/></svg>
                    </div>
                    <div class="pf-score-label">JEE Main Performance</div>
                    <div class="pf-score-value">{{ $jeeScore }}%</div>
                    <div class="pf-score-sub">Predicted Percentile: <b>{{ $jeePercentile }}</b></div>
                    <div class="pf-delta">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        {{ $jeeDelta }}
                    </div>
                </div>
            </div>

            {{-- performance over time --}}
            <div class="pf-card">
                <div class="pf-card-head">
                    <div class="pf-card-title">Performance Over Time</div>
                    <div class="pf-chart-tabs">
                        <button type="button" class="pf-chip is-active" data-range="30">30 Days</button>
                        <button type="button" class="pf-chip" data-range="90">3 Months</button>
                        <button type="button" class="pf-chip" data-range="365">Academic Year</button>
                    </div>
                </div>
                <div class="pf-legend">
                    <span data-view="school"><i style="background:#2563EB"></i> School Performance</span>
                    <span data-view="exam"><i style="background:#7C3AED"></i> JEE Performance</span>
                </div>
                <div class="pf-chart-box"><canvas id="pfTrendChart"></canvas></div>
            </div>

            {{-- subject performance + JEE readiness --}}
            <div class="pf-row2">
                <div class="pf-card" data-view="school">
                    <div class="pf-card-head">
                        <div class="pf-card-title">School – Subject Performance</div>
                        <a href="#" class="pf-link">View All</a>
                    </div>
                    <div class="pf-scroll">
                        <table class="pf-table">
                            <thead>
                                <tr><th>Subject</th><th>Your Score</th><th>Class Average</th><th>Trend</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($schoolSubjects as $s)
                                <tr>
                                    <td>
                                        <span class="pf-subject">
                                            <span class="pf-subject-icon {{ $s['tone'] }}">{{ $s['icon'] }}</span>
                                            {{ $s['name'] }}
                                        </span>
                                    </td>
                                    <td><b>{{ $s['score'] }}%</b></td>
                                    <td>{{ $s['avg'] }}%</td>
                                    <td>
                                        <span class="pf-trend {{ $s['trend'] }}">
                                            @if ($s['trend'] === 'up') ↗ @elseif ($s['trend'] === 'down') ↘ @else → @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pf-card" data-view="exam">
                    <div class="pf-card-head">
                        <div class="pf-card-title">Exam – JEE Readiness</div>
                        <a href="#" class="pf-link">View All</a>
                    </div>
                    <div class="pf-scroll">
                        <table class="pf-table">
                            <thead>
                                <tr><th>Subject</th><th>Readiness</th><th>Accuracy</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($jeeReadiness as $r)
                                <tr>
                                    <td>
                                        <span class="pf-subject">
                                            <span class="pf-subject-icon {{ $r['tone'] }}">{{ $r['icon'] }}</span>
                                            {{ $r['name'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="pf-cellbar">
                                            <span class="pf-bar"><i style="width:{{ $r['readiness'] }}%"></i></span>
                                        </span>
                                    </td>
                                    <td><b>{{ $r['accuracy'] }}</b></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pf-predict">
                        @foreach ($jeePrediction as $p)
                            <div><span>{{ $p['label'] }}</span><span>{{ $p['value'] }}</span></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- academic health --}}
            <div class="pf-card" data-view="school">
                <div class="pf-card-head"><div class="pf-card-title">Academic Health (School)</div></div>
                <div class="pf-health">
                    @foreach ($academicHealth as $h)
                        <div class="pf-health-box {{ $h['tone'] }}">
                            <div class="pf-health-label"><span class="pf-dot"></span>{{ $h['label'] }}</div>
                            @foreach ($h['items'] as $item)
                                <div class="pf-health-item"><span class="pf-dot" style="background:currentColor;opacity:.55"></span>{{ $item }}</div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="pf-health-note">
                        <div class="pf-trophy">🏆</div>
                        <b>You're doing great!</b>
                        <small>Keep up the consistency to achieve your goals.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------- RIGHT COLUMN ---------- --}}
        <div class="pf-col">

            {{-- study overview --}}
            <div class="pf-card">
                <div class="pf-card-head"><div class="pf-card-title">Study Overview</div></div>
                @foreach ($studyOverview as $stat)
                    <div class="pf-stat">
                        <div class="pf-stat-icon {{ $stat['tone'] }}">
                            @if ($stat['icon'] === 'calendar')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                            @elseif ($stat['icon'] === 'clock')
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            @else
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="pf-stat-value">{{ $stat['value'] }}</div>
                            <div class="pf-stat-label">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- upcoming dates --}}
            <div class="pf-card">
                <div class="pf-card-head">
                    <div class="pf-card-title">Upcoming Important Dates</div>
                    <a href="#" class="pf-link">View All</a>
                </div>
                @foreach ($upcomingDates as $e)
                    <div class="pf-event">
                        <div class="pf-event-date">
                            <b>{{ $e['day'] }}</b>
                            <small>{{ $e['month'] }}</small>
                        </div>
                        <div>
                            <div class="pf-event-title">{{ $e['title'] }}</div>
                            <div class="pf-event-meta">{{ $e['type'] }}</div>
                            <div class="pf-event-time">
                                {{ $e['time'] }}
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- AI coach --}}
            <div class="pf-coach">
                <div class="pf-coach-head">
                    <div class="pf-coach-avatar">🤖</div>
                    <h3>AI Performance Coach</h3>
                </div>
                <p>Your Maths accuracy has improved <b>12% in the last 30 days!</b> Focus more on <b>Calculus and Coordinate Geometry</b> to improve your JEE score.</p>
                <a href="#" class="pf-btn">View Recommendations</a>
            </div>

            {{-- actual exam result --}}
            <div class="pf-card" data-view="exam">
                <div class="pf-card-head">
                    <div class="pf-card-title">Actual Exam Result</div>
                    <a href="#" class="pf-link">View All</a>
                </div>
                <div class="pf-result-session">{{ $actualExam['session'] }}</div>
                <div class="pf-result-nums">
                    <div><b>{{ $actualExam['score'] }} <span>{{ $actualExam['scoreOutOf'] }}</span></b><small>Score</small></div>
                    <div><b>{{ $actualExam['percentile'] }}</b><small>Percentile</small></div>
                    <div><b>{{ $actualExam['rank'] }}</b><small>AIR</small></div>
                </div>
                <div class="pf-card-title" style="font-size:12.5px;margin-bottom:8px;">Prediction vs Actual</div>
                <div class="pf-scroll">
                    <table class="pf-table" style="min-width:0;">
                        <thead><tr><th>Metric</th><th>Predicted</th><th>Actual</th><th>Difference</th></tr></thead>
                        <tbody>
                        @foreach ($actualExam['rows'] as $row)
                            <tr>
                                <td>{{ $row['metric'] }}</td>
                                <td style="color:var(--pf-muted)">{{ $row['predicted'] }}</td>
                                <td><b>{{ $row['actual'] }}</b></td>
                                <td class="{{ $row['good'] ? 'pf-good' : '' }}">{{ $row['diff'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= BOTTOM ROW ================= --}}
    <div class="pf-bottom">
        <div class="pf-card" data-view="exam">
            <div class="pf-card-head">
                <div class="pf-card-title">Test Performance (JEE)</div>
                <a href="#" class="pf-link">View All</a>
            </div>
            <div class="pf-donut-wrap">
                <div class="pf-donut-box">
                    <canvas id="pfDonutChart"></canvas>
                    <div class="pf-donut-center">
                        <b>{{ $testAverage }}%</b>
                        <small>Average Score<br>(Last 10 Tests)</small>
                    </div>
                </div>
                <div class="pf-donut-legend">
                    @foreach ($testPerformance as $t)
                        <div>
                            <span style="background:{{ $t['color'] }}"></span>
                            <span>{{ $t['label'] }}</span>
                            <b>{{ $t['value'] }}%</b>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pf-card" data-view="exam">
            <div class="pf-card-head">
                <div class="pf-card-title">Skills Overview (JEE)</div>
                <a href="#" class="pf-link">View All</a>
            </div>
            @foreach ($skills as $skill)
                <div class="pf-skill">
                    <span>{{ $skill['label'] }}</span>
                    <span class="pf-bar"><i style="width:{{ $skill['value'] }}%"></i></span>
                    <b>{{ $skill['value'] }}%</b>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= RECOMMENDATION ================= --}}
    <div class="pf-reco">
        <div class="pf-reco-icon">💡</div>
        <div class="pf-reco-body">
            <h3>Personalized Recommendation</h3>
            <p>{{ $recommendation }}</p>
        </div>
        <a href="#" class="pf-btn pf-btn-solid">Optimize My Plan →</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------------------------
     * Data handed over from PHP
     * ---------------------------------------------------------------- */
    const TREND_LABELS = @json($trendLabels);
    const TREND_SCHOOL = @json($trendSchool);
    const TREND_JEE    = @json($trendJee);
    const DONUT_LABELS = @json(array_column($testPerformance, 'label'));
    const DONUT_VALUES = @json(array_column($testPerformance, 'value'));
    const DONUT_COLORS = @json(array_column($testPerformance, 'color'));

    /* ------------------------------------------------------------------
     * Line chart — Performance Over Time
     * ---------------------------------------------------------------- */
    const trendCanvas = document.getElementById('pfTrendChart');
    let trendChart = null;

    if (trendCanvas && window.Chart) {
        const baseLine = {
            fill: false,
            tension: 0.35,
            borderWidth: 2,
            pointRadius: 3,
            pointHoverRadius: 5,
            pointBorderWidth: 2,
            pointBackgroundColor: '#fff'
        };

        trendChart = new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: TREND_LABELS,
                datasets: [
                    Object.assign({}, baseLine, {
                        label: 'School Performance',
                        data: TREND_SCHOOL,
                        borderColor: '#2563EB',
                        pointBorderColor: '#2563EB'
                    }),
                    Object.assign({}, baseLine, {
                        label: 'JEE Performance',
                        data: TREND_JEE,
                        borderColor: '#7C3AED',
                        pointBorderColor: '#7C3AED'
                    })
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1A1D2B',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function (ctx) { return ctx.dataset.label + ': ' + ctx.parsed.y + '%'; }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0, max: 100,
                        ticks: { stepSize: 25, color: '#9AA0B0', font: { size: 11 } },
                        grid: { color: '#F0F1F6', drawTicks: false },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            color: '#9AA0B0',
                            font: { size: 11 },
                            autoSkip: false,
                            callback: function (val, index) {
                                const label = TREND_LABELS[index];
                                return label === '' ? null : label;
                            }
                        },
                        grid: { display: false },
                        border: { color: '#EDEFF5' }
                    }
                }
            }
        });
    }

    /* ------------------------------------------------------------------
     * Donut chart — Test Performance
     * ---------------------------------------------------------------- */
    const donutCanvas = document.getElementById('pfDonutChart');
    if (donutCanvas && window.Chart) {
        new Chart(donutCanvas, {
            type: 'doughnut',
            data: {
                labels: DONUT_LABELS,
                datasets: [{
                    data: DONUT_VALUES,
                    backgroundColor: DONUT_COLORS,
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1A1D2B',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (ctx) { return ctx.label + ': ' + ctx.parsed + '%'; }
                        }
                    }
                }
            }
        });
    }

    /* ------------------------------------------------------------------
     * Combined / School / Exam tabs
     * Any element carrying data-view="school" or data-view="exam" is
     * hidden when the other tab is active. Everything untagged always
     * stays visible.
     * ---------------------------------------------------------------- */
    const tabs   = document.querySelectorAll('.pf-tab');
    const scoped = document.querySelectorAll('.perf-page [data-view]');

    function applyTab(tab) {
        scoped.forEach(function (el) {
            const show = (tab === 'combined') || (el.dataset.view === tab);
            el.classList.toggle('is-hidden', !show);
        });

        // keep the line chart in sync with the tab
        if (trendChart) {
            trendChart.setDatasetVisibility(0, tab !== 'exam');   // school line
            trendChart.setDatasetVisibility(1, tab !== 'school'); // jee line
            trendChart.update();
        }
    }

    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabs.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            applyTab(btn.dataset.tab);
        });
    });

    /* ------------------------------------------------------------------
     * 30 Days / 3 Months / Academic Year chips
     * Visual state only for now — hook the fetch in where marked.
     * ---------------------------------------------------------------- */
    const chips = document.querySelectorAll('.pf-chip');
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            chips.forEach(function (c) { c.classList.remove('is-active'); });
            chip.classList.add('is-active');
            // TODO: fetch new series for chip.dataset.range and call
            // trendChart.data.datasets[n].data = [...]; trendChart.update();
        });
    });

    /* ------------------------------------------------------------------
     * Class selector — reload with the chosen class
     * ---------------------------------------------------------------- */
    const classSelect = document.getElementById('pfClassSelect');
    if (classSelect) {
        classSelect.addEventListener('change', function () {
            // TODO: window.location = '{{ url()->current() }}?class=' + encodeURIComponent(this.value);
        });
    }
});
</script>
@endpush