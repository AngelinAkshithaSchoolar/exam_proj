{{--
    Screen 5 — question-by-question review.

    This is the ONLY view that receives AlpCbt1Bank::all() with the answer
    key intact, and the controller only renders it once submitted_at is set.
    Do not reuse this data shape on any pre-submission screen.

    Filters are client-side because all 75 rows are already on the page —
    a round trip to hide 50 of them would be silly.
--}}
@extends('layouts.app')

@section('title', 'Answer Review — RRB ALP CBT 1')
@section('page-title', 'Answer Review')
@section('page-sub', 'RRB ALP 2026 — CBT 1 (First Stage): Full Live Test')

@push('styles')
<style>
.page-content .rv-bar{display:flex;gap:9px;flex-wrap:wrap;align-items:center;margin-bottom:18px}
.page-content .chip{border:1.5px solid var(--border);background:#fff;border-radius:99px;
        padding:8px 15px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;
        color:var(--text)}
.page-content .chip.on{background:var(--indigo);border-color:var(--indigo);color:#fff}
.page-content .chip .n{opacity:.7;font-weight:600}

.page-content .qcard{background:var(--card);border:1px solid var(--border);border-left-width:5px;
        border-radius:12px;padding:18px 20px;margin-bottom:13px;box-shadow:var(--shadow)}
.page-content .qcard.correct{border-left-color:var(--green)}
.page-content .qcard.wrong{border-left-color:var(--red)}
.page-content .qcard.skipped{border-left-color:#C9CDDB}
.page-content .qcard__top{display:flex;justify-content:space-between;gap:14px;
        align-items:flex-start;margin-bottom:12px}
.page-content .qcard__q{font-size:15px;font-weight:700;line-height:1.6}
.page-content .tag{font-size:11px;font-weight:800;padding:4px 10px;border-radius:99px;
        white-space:nowrap;flex-shrink:0}
.page-content .opt{padding:9px 13px;border-radius:8px;border:1px solid var(--border);
        background:#F8F9FC;font-size:14px;margin-bottom:7px;display:flex;gap:9px;align-items:flex-start}
.page-content .opt.key{border-color:var(--green);background:#EAF9F1}
.page-content .opt.mine{border-color:var(--red);background:#FDEEF2}
.page-content .opt .flag{margin-left:auto;font-size:11.5px;font-weight:800;white-space:nowrap}
.page-content .rv-empty{padding:40px;text-align:center;color:var(--muted)}
</style>
@endpush

@section('content')

<div class="rv-bar">
    <button type="button" class="chip on" data-filter="all">All <span class="n">{{ $r['total'] }}</span></button>
    <button type="button" class="chip" data-filter="correct">Correct <span class="n">{{ $r['correct'] }}</span></button>
    <button type="button" class="chip" data-filter="wrong">Incorrect <span class="n">{{ $r['wrong'] }}</span></button>
    <button type="button" class="chip" data-filter="skipped">Skipped <span class="n">{{ $r['skipped'] }}</span></button>

    <span style="width:1px;height:24px;background:var(--border);margin:0 4px"></span>

    @foreach ($sections as $sk => $meta)
        <button type="button" class="chip" data-section="{{ $sk }}">
            {{ $meta['short'] }} <span class="n">{{ $r['by_section'][$sk]['total'] }}</span>
        </button>
    @endforeach

    <span style="margin-left:auto">
        <a href="{{ route('alp-cbt1.result') }}" style="font-weight:700;text-decoration:none">← Back to result</a>
    </span>
</div>

<div id="rvList">
@foreach ($questions as $i => $q)
    @php
        $mine   = $answers[$i] ?? null;
        $status = $mine === null ? 'skipped' : ($mine === $q['a'] ? 'correct' : 'wrong');
        $body   = $q[$lang] ?? $q['en'];
        $meta   = $sections[$q['s']];
    @endphp

    <article class="qcard {{ $status }}" data-status="{{ $status }}" data-section="{{ $q['s'] }}">
        <div class="qcard__top">
            <div class="qcard__q">Q{{ $i + 1 }}. {{ $body['q'] }}</div>
            <span class="tag" style="background:{{ $meta['color'] }}18;color:{{ $meta['color'] }}">
                {{ $meta['short'] }}
            </span>
        </div>

        @foreach ($body['o'] as $oi => $opt)
            <div class="opt {{ $oi === $q['a'] ? 'key' : '' }} {{ ($mine === $oi && $mine !== $q['a']) ? 'mine' : '' }}">
                <b>{{ chr(65 + $oi) }})</b>
                <span>{{ $opt }}</span>
                @if ($oi === $q['a'])
                    <span class="flag" style="color:var(--green)">✓ Correct answer</span>
                @elseif ($mine === $oi)
                    <span class="flag" style="color:var(--red)">✗ Your answer</span>
                @endif
            </div>
        @endforeach

        @if ($mine === null)
            <div style="font-size:13px;color:var(--muted);margin-top:8px">
                Not attempted — no marks awarded, no penalty applied.
            </div>
        @endif
    </article>
@endforeach
</div>

<div class="rv-empty" id="rvEmpty" hidden>No questions match this filter.</div>

@endsection

@push('scripts')
<script>
(function () {
    var status = 'all', section = 'all';
    var cards  = Array.prototype.slice.call(document.querySelectorAll('.qcard'));
    var empty  = document.getElementById('rvEmpty');

    function apply() {
        var shown = 0;

        cards.forEach(function (c) {
            var ok = (status === 'all' || c.dataset.status === status) &&
                     (section === 'all' || c.dataset.section === section);
            c.hidden = !ok;
            if (ok) shown++;
        });

        empty.hidden = shown > 0;
    }

    document.querySelectorAll('.chip[data-filter]').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('.chip[data-filter]').forEach(function (x) { x.classList.remove('on'); });
            b.classList.add('on');
            status = b.dataset.filter;
            apply();
        });
    });

    document.querySelectorAll('.chip[data-section]').forEach(function (b) {
        b.addEventListener('click', function () {
            var already = b.classList.contains('on');
            document.querySelectorAll('.chip[data-section]').forEach(function (x) { x.classList.remove('on'); });
            // Clicking the active section chip again clears the section filter.
            if (already) { section = 'all'; } else { b.classList.add('on'); section = b.dataset.section; }
            apply();
        });
    });
})();
</script>
@endpush
