{{--
    Screen 4 — the score card.

    This one DOES sit inside the dashboard shell: the exam is over, the
    candidate is back in the app, and they should be able to reach the
    sidebar again.

    A word on the numbers, because two of them are easy to misread:
      • Score is out of 75 AFTER the 1/3 penalty, so it can be lower than
        the number of correct answers and can go negative.
      • Accuracy is out of what was ATTEMPTED, not out of 75. Answering 10
        and getting 9 right is 90% accurate and 8.67 marks.

    There is no rank here, and that is deliberate — a rank needs every other
    candidate's attempt in a database. See the note at the bottom of the page.
--}}
@extends('layouts.app')

@section('title', 'Result — RRB ALP CBT 1')
@section('page-title', 'Test Result')
@section('page-sub', 'RRB ALP 2026 — CBT 1 (First Stage): Full Live Test')

@push('styles')
<style>
.page-content .res-hero{background:linear-gradient(135deg,#4B3BD6,#6D28D9);color:#fff;
        border-radius:16px;padding:30px;text-align:center;margin-bottom:22px}
.page-content .res-hero h2{font-size:24px;font-weight:800;margin-bottom:22px}
.page-content .res-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.page-content .res-card{background:#fff;border-radius:13px;padding:20px;color:var(--text)}
.page-content .res-card h4{font-size:13px;font-weight:800;color:var(--muted);
        text-transform:uppercase;letter-spacing:.4px;margin-bottom:12px}
.page-content .res-big{font-size:32px;font-weight:800;line-height:1.1}
.page-content .res-sub{font-size:12px;color:var(--muted);margin-top:3px}
.page-content .res-split{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.page-content .res-dot{display:flex;align-items:center;gap:9px;font-size:13.5px;margin-bottom:9px}
.page-content .res-dot i{width:11px;height:11px;border-radius:50%;flex-shrink:0}
.page-content .res-dot b{margin-left:auto;font-size:15px}

.page-content .panel{background:var(--card);border:1px solid var(--border);border-radius:14px;
        padding:20px;margin-bottom:18px;box-shadow:var(--shadow)}
.page-content .panel h3{font-size:16px;font-weight:800;margin-bottom:14px}

.page-content .sec-row{margin-bottom:15px}
.page-content .sec-row__top{display:flex;justify-content:space-between;align-items:baseline;
        font-size:13.5px;margin-bottom:6px;gap:12px}
.page-content .bar{height:8px;border-radius:99px;background:#EEF0F6;overflow:hidden}
.page-content .bar i{display:block;height:100%;border-radius:99px}

.page-content .qual{display:grid;grid-template-columns:repeat(2,1fr);gap:11px}
.page-content .qual div{padding:12px 14px;border-radius:10px;font-size:13.5px;
        border-left:4px solid var(--border);background:#F7F8FC}
.page-content .qual .yes{border-left-color:var(--green);background:#EAF9F1}
.page-content .qual .no{border-left-color:var(--red);background:#FDEEF2}

.page-content .warnbox{background:#FFF6E8;border-left:4px solid var(--orange);
        border-radius:10px;padding:14px 16px;font-size:13.5px;line-height:1.7;margin-bottom:18px}
.page-content .infobox{background:#EEF3FF;border-left:4px solid var(--blue);
        border-radius:10px;padding:14px 16px;font-size:13.5px;line-height:1.7}
.page-content .res-actions{display:flex;gap:11px;flex-wrap:wrap;margin-top:6px}
.page-content .btn{border:0;border-radius:10px;padding:12px 20px;font-size:14px;font-weight:700;
        cursor:pointer;text-decoration:none;display:inline-block;color:#fff;background:var(--indigo)}
.page-content .btn.ghost{background:#fff;color:var(--indigo);border:1.5px solid var(--indigo)}
@media(max-width:900px){
    .page-content .res-cards,.page-content .res-split,.page-content .qual{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')

@php
    // Everything below is derived once, here, so the markup stays readable.
    $passUR   = $r['percentage'] >= 40;
    $minutes  = intdiv($r['seconds_used'], 60);
    $seconds  = $r['seconds_used'] % 60;
@endphp

@if ($r['auto_submitted'])
    <div class="warnbox" style="border-left-color:var(--red);background:#FDEEF2">
        ⏰ <b>Time expired.</b> Your test was submitted automatically when the clock reached zero.
        Everything you had saved up to that moment has been marked.
    </div>
@endif

<div class="res-hero">
    <h2>Thank you for attempting RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</h2>

    <div class="res-cards">
        <div class="res-card">
            <h4>Score</h4>
            <div class="res-big" style="color:{{ $passUR ? 'var(--green)' : 'var(--red)' }}">
                {{ number_format($r['score'], 2) }}<span style="font-size:18px;color:var(--muted)">/{{ (int) $r['max'] }}</span>
            </div>
            <div class="res-sub">{{ $r['percentage'] }}% · after a {{ number_format($r['penalty'], 2) }} mark penalty</div>
        </div>

        <div class="res-card">
            <h4>Attempts</h4>
            <div class="res-split">
                <div>
                    <div class="res-big">{{ $r['attempted'] }}</div>
                    <div class="res-sub">of {{ $r['total'] }} questions</div>
                </div>
                <div>
                    <div class="res-big">{{ $r['accuracy'] }}%</div>
                    <div class="res-sub">accuracy (of attempted)</div>
                </div>
            </div>
            <div class="res-sub" style="margin-top:12px">
                {{ $r['speed'] }} Q/min · {{ $minutes }}m {{ $seconds }}s used of {{ $duration }} min
            </div>
        </div>

        <div class="res-card">
            <h4>Breakdown</h4>
            <div class="res-dot"><i style="background:var(--green)"></i> Correct <b>{{ $r['correct'] }}</b></div>
            <div class="res-dot"><i style="background:var(--red)"></i> Incorrect <b>{{ $r['wrong'] }}</b></div>
            <div class="res-dot"><i style="background:#C9CDDB"></i> Skipped <b>{{ $r['skipped'] }}</b></div>
        </div>
    </div>
</div>

<div class="panel">
    <h3>Section-wise performance</h3>

    @foreach ($r['by_section'] as $key => $row)
        @php $pct = $row['total'] > 0 ? round($row['correct'] / $row['total'] * 100) : 0; @endphp
        <div class="sec-row">
            <div class="sec-row__top">
                <span style="font-weight:700;color:{{ $sections[$key]['color'] }}">
                    {{ $sections[$key]['en'] }} <span style="color:var(--muted);font-weight:500">({{ $row['total'] }} Q)</span>
                </span>
                <span style="font-weight:700">
                    {{ $row['correct'] }} correct · {{ $row['wrong'] }} wrong · {{ $row['skipped'] }} skipped
                    — <span style="color:{{ $row['score'] >= $row['total'] * 0.4 ? 'var(--green)' : 'var(--red)' }}">
                        {{ number_format($row['score'], 2) }}/{{ $row['total'] }}
                    </span>
                </span>
            </div>
            <div class="bar"><i style="width:{{ $pct }}%;background:{{ $sections[$key]['color'] }}"></i></div>
        </div>
    @endforeach
</div>

<div class="panel">
    <h3>Qualifying status by category</h3>
    <div class="qual">
        @foreach ($qualify as $label => $cut)
            @php
                $needed  = round($cut / 100 * $r['max'], 2);
                $cleared = $r['score'] >= $needed;
            @endphp
            <div class="{{ $cleared ? 'yes' : 'no' }}">
                <b>{{ $label }} ({{ $cut }}%)</b> — {{ $cleared ? 'Qualified' : 'Not qualified' }}
                <div style="color:var(--muted);margin-top:3px">Needs {{ $needed }}/{{ (int) $r['max'] }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="warnbox">
    <b>What the negative marking cost you.</b><br>
    {{ $r['wrong'] }} wrong {{ $r['wrong'] === 1 ? 'answer' : 'answers' }} × 1/3 = <b>{{ number_format($r['penalty'], 2) }}</b> marks deducted.<br>
    {{ $r['correct'] }} − {{ number_format($r['penalty'], 2) }} = <b>{{ number_format($r['score'], 2) }}</b> out of {{ (int) $r['max'] }}.<br>
    Every 3 wrong answers wipe out 1 correct one — which is why blind guessing is worse than leaving a question blank.
</div>

<div class="infobox">
    <b>Remember what CBT 1 actually is.</b> It is a screening test. These marks decide only whether you
    are shortlisted for CBT 2 — they are <b>not</b> carried into the final merit list. Shortlisting is done
    on normalised marks, up to 15 times the number of vacancies.
    <div style="margin-top:8px;color:var(--muted)">
        No rank is shown here. A rank needs every other candidate's attempt stored in a database — see the
        note in <code>AlpCbt1Controller</code> for where to add that.
    </div>
</div>

<div class="res-actions">
    <a href="{{ route('alp-cbt1.review') }}" class="btn">Review answers</a>
    <form method="POST" action="{{ route('alp-cbt1.retake') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn ghost">Retake test</button>
    </form>
    <a href="{{ route('mock-history.index') }}" class="btn ghost">Back to Mock History</a>
</div>

@endsection
