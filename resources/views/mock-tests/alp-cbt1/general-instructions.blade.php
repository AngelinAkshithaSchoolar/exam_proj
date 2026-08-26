{{--
    Screen 1 of the flow — the generic "how this CBT software works" sheet.
    Same wording every candidate sees in the real exam, so it is deliberately
    verbatim and not shortened. Nothing here is test-specific.

    Next → mock-tests/alp-cbt1/instructions
--}}
@extends('layouts.exam')

@section('title', 'General Instructions — RRB ALP CBT 1')

@section('content')
<div class="ex-instr">

    <div class="ex-instr__main">
        <div class="ex-instr__scroll">

            <p class="ex-lead" style="font-size:17px">General Instructions:</p>

            <ol class="ex-list">
                <li>
                    The clock will be set at the server. The countdown timer at the top right corner of
                    screen will display the remaining time available for you to complete the examination.
                    When the timer reaches zero, the examination will end by itself. You need not terminate
                    the examination or submit your paper.
                </li>

                <li>
                    The Question Palette displayed on the right side of screen will show the status of each
                    question using one of the following symbols:

                    <ul class="ex-legend">
                        <li><span class="legend-chip sq"></span> You have not visited the question yet.</li>
                        <li><span class="legend-chip not-answered"></span> You have not answered the question.</li>
                        <li><span class="legend-chip answered"></span> You have answered the question.</li>
                        <li><span class="legend-chip marked"></span> You have NOT answered the question, but have marked the question for review.</li>
                        <li><span class="legend-chip marked-answered"></span> You have answered the question, but marked it for review.</li>
                    </ul>

                    <p>
                        The <b>Mark For Review</b> status for a question simply indicates that you would like
                        to look at that question again. If a question is answered, but marked for review, then
                        the answer will be considered for evaluation unless the status is modified by the
                        candidate.
                    </p>
                </li>
            </ol>

            <p class="ex-h3">Navigating to a Question:</p>

            <ol class="ex-list" start="3">
                <li>
                    To answer a question, do the following:
                    <ol type="1">
                        <li>
                            Click on the question number in the Question Palette at the right of your screen to
                            go to that numbered question directly. Note that using this option does NOT save
                            your answer to the current question.
                        </li>
                        <li>Click on <b>Save &amp; Next</b> to save your answer for the current question and then go to the next question.</li>
                        <li>Click on <b>Mark for Review &amp; Next</b> to save your answer for the current question and also mark it for review, and then go to the next question.</li>
                    </ol>
                </li>
            </ol>

            <p style="margin-bottom:14px">
                Note that your answer for the current question will not be saved, if you navigate to another
                question directly by clicking on a question number without saving the answer to the previous
                question.
            </p>

            <p style="margin-bottom:14px">
                You can view all the questions by clicking on the <b>Question Paper</b> button.
                <span class="ex-note">This feature is provided, so that if you want you can just see the entire question paper at a glance.</span>
            </p>

            <p class="ex-h3">Answering a Question:</p>

            <ol class="ex-list" start="4">
                <li>
                    Procedure for answering a multiple choice (MCQ) type question:
                    <ol type="1">
                        <li>Choose one answer from the 4 options (A, B, C, D) given below the question, click on the bubble placed before the chosen option.</li>
                        <li>To deselect your chosen answer, click on the bubble of the chosen option again or click on the <b>Clear Response</b> button.</li>
                        <li>To change your chosen answer, click on the bubble of another option.</li>
                        <li>To save your answer, you MUST click on the <b>Save &amp; Next</b>.</li>
                    </ol>
                </li>

                <li>
                    Procedure for answering a numerical answer type question:
                    <ol type="1">
                        <li>To enter a number as your answer, use the virtual numerical keypad.</li>
                        <li>
                            A fraction (e.g. -0.3 or -.3) can be entered as an answer with or without "0" before
                            the decimal point.
                            <span class="ex-note">As many as four decimal points, e.g. 12.5435 or 0.003 or -932.6711 or 12.82 can be entered.</span>
                        </li>
                        <li>To clear your answer, click on the <b>Clear Response</b> button.</li>
                        <li>To save your answer, you MUST click on the <b>Save &amp; Next</b>.</li>
                    </ol>
                </li>

                <li>
                    To mark a question for review, click on the <b>Mark for Review &amp; Next</b> button. If an
                    answer is selected (for MCQ/MCAQ) entered (for numerical answer type) for a question that is
                    <b>Marked for Review</b>, that answer will be considered in the evaluation unless the status
                    is modified by the candidate.
                </li>

                <li>To change your answer to a question that has already been answered, first select that question for answering and then follow the procedure for answering that type of question.</li>

                <li>Note that ONLY Questions for which answers are <b>saved</b> or <b>marked for review after answering</b> will be considered for evaluation.</li>

                <li>Sections in this question paper are displayed on the top bar of the screen. Questions in a Section can be viewed by clicking on the name of that Section. The Section you are currently viewing will be highlighted.</li>

                <li>After clicking the <b>Save &amp; Next</b> button for the last question in a Section, you will automatically be taken to the first question of the next Section in sequence.</li>

                <li>You can move the mouse cursor over the name of a Section to view the answering status for that Section.</li>
            </ol>

        </div>

        <div class="ex-instr__foot">
            <a href="{{ route('mock-tests.index') }}" style="text-decoration:none;font-weight:600">← Go to Tests</a>
            <a href="{{ route('alp-cbt1.instructions') }}" class="ex-btn soft wide" style="text-decoration:none">Next</a>
        </div>
    </div>

    <aside class="ex-instr__aside">
        <div class="candidate-avatar">{{ mb_strtoupper(mb_substr($candidate, 0, 1)) }}</div>
        <div class="candidate-name">{{ $candidate }}</div>
    </aside>

</div>
@endsection
