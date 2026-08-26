{{--
    Screen 3 — the test window.

    Read this before changing anything about saving:

    A selected radio button is NOT an answer. In a real CBT the response is
    only recorded when the candidate presses "Save & Next" or "Mark for
    Review & Next"; jumping straight to another question from the palette
    throws the selection away. That is stated in the general instructions,
    it is what candidates are drilled on, and it is reproduced faithfully
    here — `pending` (what the radio shows) is a different variable from
    `saved` (what will be marked).

    The countdown is display only. The server stamped started_at when the
    candidate pressed "I am ready to begin" and hands this page the seconds
    that were left at render time; AlpCbt1Controller decides whether a
    submission arrived in time. Editing the JS clock changes the number on
    screen and nothing else.

    The question payload carries no answer key — see AlpCbt1Bank::forPlayer().
--}}
@extends('layouts.exam')

@section('title', 'RRB ALP CBT 1 — Live Test')

@section('content')
<div class="ex-app">

    {{-- ═══════════════ Top bar ═══════════════ --}}
    <header class="ex-top">
        <div class="ex-top__name">RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</div>

        <div class="ex-clock" id="clock">
            <span class="ex-clock__label">Time Left</span>
            <span class="ex-clock__box" id="clkH">00</span>:<span class="ex-clock__box" id="clkM">00</span>:<span class="ex-clock__box" id="clkS">00</span>
        </div>

        <button type="button" class="ex-fs" onclick="exFullscreen()">Switch Full Screen</button>
    </header>

    {{-- ═══════════════ Section tabs ═══════════════ --}}
    <nav class="ex-sections" role="tablist" aria-label="Sections">
        <span class="ex-sections__label">SECTIONS</span>
        @foreach ($sections as $key => $meta)
            <button type="button" class="sec-tab" role="tab"
                    id="tab-{{ $key }}" data-section="{{ $key }}"
                    data-first="{{ $ranges[$key][0] ?? 1 }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    title="{{ $meta['en'] }} — {{ count($ranges[$key]) }} questions">
                {{ $meta['short'] }}
            </button>
        @endforeach
    </nav>

    <div class="ex-body">

        {{-- ═══════════════ Question column ═══════════════ --}}
        <main class="ex-q">

            <div class="ex-q__head">
                <div class="ex-q__no">Question No. <span id="qNo">1</span></div>

                <div class="ex-q__stats">
                    <div>
                        Marks
                        <div style="margin-top:3px">
                            <span class="mark-pos">+1</span>
                            <span class="mark-neg">-0.33</span>
                        </div>
                    </div>

                    <div>
                        Time
                        <b id="qTime">00:00</b>
                    </div>

                    <div class="ex-q__lang">
                        <label for="viewIn">View in</label>
                        <select id="viewIn">
                            <option value="en" @selected($lang === 'en')>English</option>
                            <option value="hi" @selected($lang === 'hi')>हिन्दी</option>
                        </select>
                    </div>

                    <button type="button" class="ex-report"
                            onclick="exToast('Reported. Our team will review this question.')">
                        ⚠ Report
                    </button>
                </div>
            </div>

            <div class="ex-q__scroll">
                <p class="ex-q__text" id="qText"></p>

                <ul class="ex-opts" id="qOpts">
                    @for ($i = 0; $i < 4; $i++)
                        <li>
                            <label class="ex-opt">
                                <input type="radio" name="choice" value="{{ $i }}">
                                <span data-opt="{{ $i }}"></span>
                            </label>
                        </li>
                    @endfor
                </ul>
            </div>

            <footer class="ex-foot">
                <div class="ex-foot__left">
                    <button type="button" class="ex-btn soft" id="btnMark">Mark for Review &amp; Next</button>
                    <button type="button" class="ex-btn soft" id="btnClear">Clear Response</button>
                </div>
                <button type="button" class="ex-btn soft" id="btnSave" style="min-width:130px">Save &amp; Next</button>
            </footer>
        </main>

        {{-- ═══════════════ Right panel ═══════════════ --}}
        <aside class="ex-side">

            <div class="ex-side__who">
                <div class="av">{{ mb_strtoupper(mb_substr($candidate, 0, 1)) }}</div>
                <b>{{ $candidate }}</b>
            </div>

            <div class="ex-counts">
                <span><i class="cnt answered" id="cAnswered">0</i> Answered</span>
                <span><i class="cnt marked" id="cMarked">0</i> Marked</span>
                <span><i class="cnt not-visited" id="cNotVisited">{{ $total }}</i> Not Visited</span>
                <span><i class="cnt marked-answered" id="cMarkedAnswered">0</i> Marked and answered</span>
                <span><i class="cnt not-answered" id="cNotAnswered">0</i> Not Answered</span>
            </div>

            <div class="ex-side__sec">SECTION : <span id="secName">{{ reset($sections)['en'] }}</span></div>

            <div class="ex-palette" id="palette"></div>

            <div class="ex-side__foot">
                <button type="button" class="ex-btn soft" onclick="openModal('paperModal')">Question Paper</button>
                <button type="button" class="ex-btn soft" onclick="openModal('instrModal')">Instructions</button>
                <button type="button" class="ex-btn submit" id="btnSubmit">Submit Test</button>
            </div>
        </aside>
    </div>
</div>

{{-- ═══════════════ Submit confirmation ═══════════════ --}}
<div class="ex-modal" id="submitModal">
    <div class="ex-modal__box">
        <div class="ex-modal__head">Submit your test</div>
        <div class="ex-modal__body">
            <table class="ex-table">
                <thead>
                    <tr>
                        <th style="text-align:left">Section</th>
                        <th>No. of questions</th>
                        <th>Answered</th>
                        <th>Not Answered</th>
                        <th>Marked for Review</th>
                        <th>Not Visited</th>
                    </tr>
                </thead>
                <tbody id="summaryBody"></tbody>
            </table>
        </div>
        <div class="ex-modal__foot">
            <button type="button" class="ex-btn soft" onclick="closeModal('submitModal')">Close</button>
            <button type="button" class="ex-btn" id="btnSubmitConfirm">Submit</button>
        </div>
    </div>
</div>

{{-- ═══════════════ Question paper at a glance ═══════════════ --}}
<div class="ex-modal" id="paperModal">
    <div class="ex-modal__box" style="max-width:900px">
        <div class="ex-modal__head"><b>Question Paper</b></div>
        <div class="ex-modal__body" id="paperBody"></div>
        <div class="ex-modal__foot">
            <button type="button" class="ex-btn soft" onclick="closeModal('paperModal')">Close</button>
        </div>
    </div>
</div>

{{-- ═══════════════ Instructions (re-readable mid-test) ═══════════════ --}}
<div class="ex-modal" id="instrModal">
    <div class="ex-modal__box">
        <div class="ex-modal__head"><b>Instructions</b></div>
        <div class="ex-modal__body">
            <ul class="ex-legend">
                <li><span class="legend-chip sq"></span> You have not visited the question yet.</li>
                <li><span class="legend-chip not-answered"></span> You have not answered the question.</li>
                <li><span class="legend-chip answered"></span> You have answered the question.</li>
                <li><span class="legend-chip marked"></span> You have NOT answered the question, but have marked it for review.</li>
                <li><span class="legend-chip marked-answered"></span> You have answered the question, but marked it for review.</li>
            </ul>
            <p style="margin-bottom:10px">
                Your answer is recorded only when you click <b>Save &amp; Next</b> or
                <b>Mark for Review &amp; Next</b>. Clicking a number in the palette moves you without saving.
            </p>
            <p>
                <b>1 mark</b> for each correct answer, <b>−1/3</b> for each wrong answer, nothing for
                questions you leave blank.
            </p>
        </div>
        <div class="ex-modal__foot">
            <button type="button" class="ex-btn soft" onclick="closeModal('instrModal')">Close</button>
        </div>
    </div>
</div>

{{-- The real submission. Populated from JS immediately before it is sent. --}}
<form method="POST" action="{{ route('alp-cbt1.submit') }}" id="submitForm" hidden>
    @csrf
    <div id="submitFields"></div>
</form>
@endsection

@push('scripts')
<script>
(function () {
'use strict';

/* ─────────────────────────── Data from the server ─────────────────────────── */
var QUESTIONS = @json($questions);
var SECTIONS  = @json($sections);
var RANGES    = @json($ranges);          // section key => [1-based question numbers]
var TOTAL     = {{ $total }};
var DEFLANG   = @json($lang);
var AUTOSAVE  = @json(route('alp-cbt1.autosave'));
var CSRF      = document.querySelector('meta[name="csrf-token"]').content;

/* ─────────────────────────── State ───────────────────────────
   saved   — index => option index. Only what was actually saved.
   marked  — index => true. Flagged for review.
   visited — index => true. Opened at least once.
   pending — the radio selection for the question on screen, not yet saved.
   ──────────────────────────────────────────────────────────── */
var saved      = @json((object) $saved);
var markedList = @json(array_values($markedSaved));
var marked     = {};
markedList.forEach(function (i) { marked[i] = true; });

var visited = {};
var pending = null;
var current = 0;                       // 0-based index
var currentLang = DEFLANG;
var qSeconds = {};                     // index => seconds spent
var remaining = {{ $remaining }};      // seconds, from the server
var submitting = false;

/* Recover anything the server already had (a mid-test refresh). */
Object.keys(saved).forEach(function (k) { visited[k] = true; });
Object.keys(marked).forEach(function (k) { visited[k] = true; });

/* ─────────────────────────── Elements ─────────────────────────── */
var el = {
    qNo:   document.getElementById('qNo'),
    qText: document.getElementById('qText'),
    qOpts: document.getElementById('qOpts'),
    qTime: document.getElementById('qTime'),
    pal:   document.getElementById('palette'),
    sec:   document.getElementById('secName'),
    viewIn: document.getElementById('viewIn'),
    clock: document.getElementById('clock'),
    h: document.getElementById('clkH'), m: document.getElementById('clkM'), s: document.getElementById('clkS')
};

/* ─────────────────────────── Helpers ─────────────────────────── */
function sectionOf(i) { return QUESTIONS[i].s; }

function pad(n) { return String(n).padStart(2, '0'); }

function stateOf(i) {
    var hasAnswer = Object.prototype.hasOwnProperty.call(saved, i);
    if (marked[i] && hasAnswer) return 'marked-answered';
    if (marked[i])              return 'marked';
    if (hasAnswer)              return 'answered';
    if (visited[i])             return 'not-answered';
    return 'not-visited';
}

/* ─────────────────────────── Palette ─────────────────────────── */
function buildPalette() {
    var key = sectionOf(current);
    el.sec.textContent = SECTIONS[key].en;
    el.pal.innerHTML = '';

    RANGES[key].forEach(function (n) {
        var i = n - 1;
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'pal' + (i === current ? ' current' : '');
        b.dataset.state = stateOf(i);
        b.textContent = n;
        b.title = 'Question ' + n;
        // Jumping from the palette does NOT save — same as the real CBT.
        b.addEventListener('click', function () { go(i); });
        el.pal.appendChild(b);
    });

    document.querySelectorAll('.sec-tab').forEach(function (t) {
        t.setAttribute('aria-selected', t.dataset.section === key ? 'true' : 'false');
    });
}

function refreshCounts() {
    var c = { answered: 0, marked: 0, 'marked-answered': 0, 'not-answered': 0, 'not-visited': 0 };
    for (var i = 0; i < TOTAL; i++) c[stateOf(i)]++;

    document.getElementById('cAnswered').textContent       = c.answered;
    document.getElementById('cMarked').textContent         = c.marked;
    document.getElementById('cMarkedAnswered').textContent = c['marked-answered'];
    document.getElementById('cNotAnswered').textContent    = c['not-answered'];
    document.getElementById('cNotVisited').textContent     = c['not-visited'];
    return c;
}

/* ─────────────────────────── Rendering ─────────────────────────── */
function render() {
    var q = QUESTIONS[current];
    var body = q[currentLang] || q.en;

    el.qNo.textContent = current + 1;
    el.qText.textContent = body.q;
    el.qText.className = 'ex-q__text' + (currentLang === 'hi' ? ' hi-text' : '');

    el.qOpts.querySelectorAll('span[data-opt]').forEach(function (span, i) {
        span.textContent = body.o[i];
        span.className = currentLang === 'hi' ? 'hi-text' : '';
    });

    el.qOpts.querySelectorAll('input[name="choice"]').forEach(function (r) {
        r.checked = (pending !== null && Number(r.value) === pending);
    });

    el.qTime.textContent = pad(Math.floor((qSeconds[current] || 0) / 60)) + ':' + pad((qSeconds[current] || 0) % 60);

    buildPalette();
    refreshCounts();
}

/**
 * Move to a question.
 *
 * A question is marked "visited" when the candidate LEAVES it, not when they
 * arrive — that is why the question you are sitting on still shows as white
 * (Not Visited) in the palette, and why the Not Visited counter only drops
 * once you move on. This matches the real CBT exactly.
 *
 * Any unsaved selection on the question being left is discarded: `pending` is
 * re-read from `saved` for the new question.
 */
function go(i) {
    if (i < 0 || i >= TOTAL || i === current) return;

    visited[current] = true;
    current = i;
    pending = Object.prototype.hasOwnProperty.call(saved, i) ? saved[i] : null;
    render();
}

/* ─────────────────────────── Actions ─────────────────────────── */
function commit() {
    if (pending === null) {
        delete saved[current];
    } else {
        saved[current] = pending;
    }
    visited[current] = true;
}

/** Save/Mark always move on — except on the very last question. */
function advance() {
    if (current < TOTAL - 1) {
        go(current + 1);
    } else {
        render();
        exToast('That was the last question. Use Submit Test when you are done.');
    }
}

document.getElementById('btnSave').addEventListener('click', function () {
    commit();
    delete marked[current];          // saving clears a review flag, as in the real CBT
    queueAutosave();
    advance();
});

document.getElementById('btnMark').addEventListener('click', function () {
    commit();
    marked[current] = true;
    queueAutosave();
    advance();
});

document.getElementById('btnClear').addEventListener('click', function () {
    pending = null;
    delete saved[current];
    el.qOpts.querySelectorAll('input[name="choice"]').forEach(function (r) { r.checked = false; });
    queueAutosave();
    render();
});

/* Clicking the already-selected bubble deselects it — instruction 4.2.
   The browser flips .checked during pre-click activation, so by the time this
   listener runs the radio is already on. We record what it WAS on mousedown
   and undo it here; `change` covers the keyboard path. */
el.qOpts.addEventListener('mousedown', function (e) {
    if (e.target.name === 'choice') e.target.dataset.was = e.target.checked ? '1' : '0';
});

el.qOpts.addEventListener('click', function (e) {
    if (e.target.name !== 'choice') return;

    if (e.target.dataset.was === '1') {
        e.target.checked = false;
        e.target.dataset.was = '0';
        pending = null;
    } else {
        pending = Number(e.target.value);
    }
});

el.qOpts.addEventListener('change', function (e) {
    if (e.target.name === 'choice') {
        pending = e.target.checked ? Number(e.target.value) : null;
    }
});

el.viewIn.addEventListener('change', function () {
    currentLang = this.value;
    render();
});

document.querySelectorAll('.sec-tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
        go(Number(this.dataset.first) - 1);
    });
});

/* ─────────────────────────── Clock ─────────────────────────── */
setInterval(function () {
    if (submitting) return;

    remaining--;
    qSeconds[current] = (qSeconds[current] || 0) + 1;

    if (remaining <= 0) {
        remaining = 0;
        paint();
        exToast('Time is up — submitting your test.');
        doSubmit(true);
        return;
    }

    paint();
    el.qTime.textContent = pad(Math.floor(qSeconds[current] / 60)) + ':' + pad(qSeconds[current] % 60);
}, 1000);

function paint() {
    var t = Math.max(0, remaining);
    el.h.textContent = pad(Math.floor(t / 3600));
    el.m.textContent = pad(Math.floor((t % 3600) / 60));
    el.s.textContent = pad(t % 60);
    el.clock.classList.toggle('warn', t <= 300);
}

/* ─────────────────────────── Autosave ───────────────────────────
   Best effort only. A failed autosave must never interrupt the exam,
   so every error is swallowed — the real answer sheet still travels
   with the submit POST.
   ──────────────────────────────────────────────────────────────── */
var autosaveTimer = null;

function queueAutosave() {
    clearTimeout(autosaveTimer);
    autosaveTimer = setTimeout(pushAutosave, 1200);
}

function pushAutosave() {
    if (submitting) return;

    fetch(AUTOSAVE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ answers: saved, marked: Object.keys(marked).map(Number) })
    }).catch(function () { /* offline — ignore */ });
}

setInterval(pushAutosave, 30000);

/* ─────────────────────────── Submit ─────────────────────────── */
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
window.openModal = openModal;
window.closeModal = closeModal;

document.getElementById('btnSubmit').addEventListener('click', function () {
    var body = document.getElementById('summaryBody');
    body.innerHTML = '';

    Object.keys(SECTIONS).forEach(function (key) {
        var nums = RANGES[key], row = { answered: 0, notAnswered: 0, marked: 0, notVisited: 0 };

        nums.forEach(function (n) {
            switch (stateOf(n - 1)) {
                case 'answered':         row.answered++;    break;
                case 'marked-answered':  row.answered++; row.marked++; break;
                case 'marked':           row.marked++;      break;
                case 'not-answered':     row.notAnswered++; break;
                default:                 row.notVisited++;
            }
        });

        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + SECTIONS[key].en + '</td><td>' + nums.length + '</td><td>' +
            row.answered + '</td><td>' + row.notAnswered + '</td><td>' +
            row.marked + '</td><td>' + row.notVisited + '</td>';
        body.appendChild(tr);
    });

    openModal('submitModal');
});

document.getElementById('btnSubmitConfirm').addEventListener('click', function () { doSubmit(false); });

function doSubmit(auto) {
    if (submitting) return;
    submitting = true;

    var fields = document.getElementById('submitFields');
    fields.innerHTML = '';

    Object.keys(saved).forEach(function (i) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'answers[' + i + ']';
        input.value = saved[i];
        fields.appendChild(input);
    });

    Object.keys(marked).forEach(function (i) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'marked[]';
        input.value = i;
        fields.appendChild(input);
    });

    if (auto) closeModal('submitModal');
    document.getElementById('submitForm').submit();
}

/* Closing the tab mid-test is the candidate's problem, but warn them once. */
window.addEventListener('beforeunload', function (e) {
    if (submitting) return;
    e.preventDefault();
    e.returnValue = '';
});

/* ─────────────────────────── Question paper ─────────────────────────── */
document.getElementById('paperBody').innerHTML = QUESTIONS.map(function (q, i) {
    var b = q[DEFLANG] || q.en;
    return '<div style="margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #EEF1F3">' +
        '<div style="font-weight:700;margin-bottom:6px">Q' + (i + 1) + '. ' + esc(b.q) + '</div>' +
        b.o.map(function (o, oi) {
            return '<div style="margin-left:14px">' + String.fromCharCode(65 + oi) + ') ' + esc(o) + '</div>';
        }).join('') + '</div>';
}).join('');

function esc(t) {
    return String(t).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

/* ─────────────────────────── Boot ─────────────────────────── */
paint();
pending = Object.prototype.hasOwnProperty.call(saved, 0) ? saved[0] : null;
render();   // NOT go(0) — that would mark question 1 as already visited.
})();
</script>
@endpush
