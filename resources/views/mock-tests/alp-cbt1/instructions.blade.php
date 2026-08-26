{{--
    Screen 2 — test-specific instructions, default-language picker, declaration.

    "I am ready to begin" is a real POST. That matters: the server stamps
    started_at when this form is submitted, and every later screen measures
    the clock from that stamp. A GET link here would let a candidate start
    the clock by accident (or restart it by refreshing).

    The button stays disabled until both the language and the declaration
    are set, exactly as the real CBT does.
--}}
@extends('layouts.exam')

@section('title', 'Instructions — RRB ALP CBT 1')

@section('content')
<div class="ex-instr">

    <form method="POST" action="{{ route('alp-cbt1.start') }}" class="ex-instr__main" id="readyForm">
        @csrf

        <div class="ex-instr__scroll">
            <h1 class="ex-title">RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</h1>

            <div class="ex-meta">
                <span>Duration: {{ $duration }} Mins</span>
                <span>Maximum Marks: {{ $total }}</span>
            </div>

            <p class="ex-lead">Read the following instructions carefully.</p>

            <ol class="ex-list">
                <li>
                    The test contains {{ count($sections) }} sections having a total of {{ $total }} questions.
                    <ul style="margin:8px 0 0 20px">
                        @foreach ($sections as $key => $meta)
                            <li>{{ $meta['en'] }} — {{ $sectionCounts[$key] }} questions</li>
                        @endforeach
                    </ul>
                </li>
                <li>Each question has 4 options out of which only one is correct.</li>
                <li>You have to finish the test in {{ $duration }} minutes.</li>
                <li>Try not to guess the answer as there is negative marking.</li>
                <li>
                    You will be awarded <b>1 mark</b> for each correct answer and
                    <b>{{ number_format($negative, 2) }} marks</b> will be deducted for each wrong answer
                    (1/3rd of a mark).
                </li>
                <li>There is no penalty for the questions that you have not attempted.</li>
                <li>Once you start the test, you will not be allowed to reattempt it. Make sure that you complete the test before you submit the test and/or close the browser.</li>
                <li>
                    CBT 1 is a <b>screening test only</b> — the marks scored here are not counted in the final
                    merit list. Candidates are shortlisted for CBT 2 on normalised marks, up to 15 times the
                    number of vacancies.
                </li>
            </ol>

            @error('declare')
                <p class="ex-note" style="font-weight:600">{{ $message }}</p>
            @enderror
            @error('lang')
                <p class="ex-note" style="font-weight:600">{{ $message }}</p>
            @enderror
        </div>

        <div class="lang-row">
            <label for="lang"><b>Choose your default language:</b></label>
            <select name="lang" id="lang" required>
                <option value="">-- Select --</option>
                <option value="en" @selected(old('lang') === 'en')>English</option>
                <option value="hi" @selected(old('lang') === 'hi')>हिन्दी</option>
            </select>
        </div>

        <div style="padding:0 26px 12px">
            <p class="ex-note">
                Please note all questions will appear in your default language. This language can be changed
                for a particular question later on
            </p>
        </div>

        <div style="padding:0 26px 10px"><b>Declaration:</b></div>

        <div class="declare-row">
            <input type="checkbox" name="declare" id="declare" value="1" @checked(old('declare'))>
            <label for="declare">I have understood and agree to all the instructions.</label>
        </div>

        <div class="ex-instr__foot">
            <a href="{{ route('alp-cbt1.general') }}" class="ex-btn soft wide" style="text-decoration:none">Previous</a>
            <button type="submit" class="ex-btn wide" id="readyBtn" disabled>I am ready to begin</button>
        </div>
    </form>

    <aside class="ex-instr__aside">
        <div class="candidate-avatar">{{ mb_strtoupper(mb_substr($candidate, 0, 1)) }}</div>
        <div class="candidate-name">{{ $candidate }}</div>
    </aside>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var lang    = document.getElementById('lang'),
        declare = document.getElementById('declare'),
        btn     = document.getElementById('readyBtn'),
        form    = document.getElementById('readyForm');

    function sync() {
        btn.disabled = !(lang.value && declare.checked);
    }

    lang.addEventListener('change', sync);
    declare.addEventListener('change', sync);
    sync();

    // Guard against a double-click firing two start() requests, which would
    // reset started_at and quietly hand the candidate extra time.
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.textContent = 'Starting…';
    });
})();
</script>
@endpush
