@extends('layouts.app')

@section('title', 'Mock Test History — schoolar.ai')
@section('page-title', 'Mock Test History')
@section('page-sub', 'View all your previous mock tests and review your performance.')

@php
    $active = 'mock-history';

    /*
     |------------------------------------------------------------------
     | Fallback data — override by passing these from a controller:
     |   return view('mock-tests.history', compact('user','stats','tests'));
     |------------------------------------------------------------------
     */
    $user = $user ?? [
        'name'   => 'Ananya Singh',
        'plan'   => 'Premium Plan',
        'avatar' => null, // e.g. asset('img/ananya.jpg')
    ];

    $activeExam     = $activeExam ?? 'NEET';
    $notifications  = $notifications ?? 3;

    $stats = $stats ?? [
        'completed'      => 28,
        'last_test'      => 'NEET Full Mock Test 15',
        'last_date'      => '12 May 2026',
        'score'          => '86/90',
        'delta'          => 8,      // % change vs previous attempt
        'delta_positive' => true,
    ];

    /*
     | type: full | sectional  (drives the badge)
     | subject: general | chemistry | biology | physics  (drives the icon + colour)
     | date: Y-m-d  — used by the "All Time" range filter
     */
    $tests = $tests ?? [
        ['title'=>'NEET Full Mock Test 15',        'exam'=>'NEET','type'=>'full',     'subject'=>'general',  'date'=>'2026-05-12','time'=>'11:00 AM','score'=>86,'total'=>90,'accuracy'=>88,'duration'=>'3h 12m'],
        ['title'=>'NEET Chemistry Sectional Test 8','exam'=>'NEET','type'=>'sectional','subject'=>'chemistry','date'=>'2026-05-09','time'=>'04:30 PM','score'=>72,'total'=>90,'accuracy'=>76,'duration'=>'48m'],
        ['title'=>'NEET Biology Sectional Test 6',  'exam'=>'NEET','type'=>'sectional','subject'=>'biology',  'date'=>'2026-05-07','time'=>'10:15 AM','score'=>82,'total'=>90,'accuracy'=>84,'duration'=>'50m'],
        ['title'=>'NEET Physics Sectional Test 7',  'exam'=>'NEET','type'=>'sectional','subject'=>'physics',  'date'=>'2026-05-05','time'=>'05:00 PM','score'=>68,'total'=>90,'accuracy'=>71,'duration'=>'47m'],
        ['title'=>'NEET Full Mock Test 14',         'exam'=>'NEET','type'=>'full',     'subject'=>'general',  'date'=>'2026-05-02','time'=>'11:00 AM','score'=>80,'total'=>90,'accuracy'=>83,'duration'=>'3h 05m'],
        ['title'=>'NEET Chemistry Sectional Test 7','exam'=>'NEET','type'=>'sectional','subject'=>'chemistry','date'=>'2026-04-30','time'=>'04:20 PM','score'=>65,'total'=>90,'accuracy'=>69,'duration'=>'46m'],
        ['title'=>'NEET Biology Sectional Test 5',  'exam'=>'NEET','type'=>'sectional','subject'=>'biology',  'date'=>'2026-04-27','time'=>'09:40 AM','score'=>78,'total'=>90,'accuracy'=>81,'duration'=>'52m'],
        ['title'=>'NEET Full Mock Test 13',         'exam'=>'NEET','type'=>'full',     'subject'=>'general',  'date'=>'2026-04-24','time'=>'11:00 AM','score'=>74,'total'=>90,'accuracy'=>77,'duration'=>'3h 08m'],
        ['title'=>'NEET Physics Sectional Test 6',  'exam'=>'NEET','type'=>'sectional','subject'=>'physics',  'date'=>'2026-04-21','time'=>'06:10 PM','score'=>61,'total'=>90,'accuracy'=>66,'duration'=>'49m'],
        ['title'=>'JEE Full Mock Test 4',           'exam'=>'JEE', 'type'=>'full',     'subject'=>'general',  'date'=>'2026-04-18','time'=>'10:00 AM','score'=>70,'total'=>90,'accuracy'=>73,'duration'=>'2h 58m'],
        ['title'=>'NEET Chemistry Sectional Test 6','exam'=>'NEET','type'=>'sectional','subject'=>'chemistry','date'=>'2026-04-15','time'=>'03:45 PM','score'=>69,'total'=>90,'accuracy'=>72,'duration'=>'44m'],
        ['title'=>'NEET Biology Sectional Test 4',  'exam'=>'NEET','type'=>'sectional','subject'=>'biology',  'date'=>'2026-04-11','time'=>'08:30 AM','score'=>84,'total'=>90,'accuracy'=>86,'duration'=>'51m'],
        ['title'=>'NEET Full Mock Test 12',         'exam'=>'NEET','type'=>'full',     'subject'=>'general',  'date'=>'2026-03-29','time'=>'11:00 AM','score'=>71,'total'=>90,'accuracy'=>75,'duration'=>'3h 01m'],
        ['title'=>'JEE Physics Sectional Test 2',   'exam'=>'JEE', 'type'=>'sectional','subject'=>'physics',  'date'=>'2026-02-20','time'=>'05:30 PM','score'=>58,'total'=>90,'accuracy'=>63,'duration'=>'45m'],
    ];

    // Icon tile palette per subject
    $tile = [
        'general'   => ['bg' => 'bg-indigo-50',  'fg' => 'text-indigo-500'],
        'chemistry' => ['bg' => 'bg-emerald-50', 'fg' => 'text-emerald-500'],
        'biology'   => ['bg' => 'bg-orange-50',  'fg' => 'text-orange-500'],
        'physics'   => ['bg' => 'bg-violet-50',  'fg' => 'text-violet-500'],
    ];

    $perPage = 6; // rows shown before "Load More"
@endphp

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@verbatim
<script>
    /* preflight OFF — it would reset the shared sidebar/topbar styling */
    tailwind.config = {
        corePlugins: { preflight: false },
        theme: { extend: {
            colors: {
                brand: { 50:'#F3F1FF',100:'#EBE7FE',200:'#D9D2FE',300:'#BFB1FC',400:'#9F86F9',
                         500:'#7C5CF6',600:'#6D3BEE',700:'#5C29DA',800:'#4D22B6',900:'#411F95' },
                ink:   { 900:'#12121C',700:'#3A3A4C',500:'#6B6B80',400:'#8A8A9E' }
            },
            boxShadow: {
                card:'0 1px 2px rgba(18,18,28,.04), 0 1px 3px rgba(18,18,28,.04)',
                pop: '0 12px 32px -8px rgba(18,18,28,.18)'
            }
        } }
    }
</script>
<style>
    .page-content{font-family:'Plus Jakarta Sans','Segoe UI',system-ui,sans-serif}
    .page-content .row-enter{animation:rowIn .28s cubic-bezier(.2,.8,.3,1) both}
    @keyframes rowIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
    .page-content [hidden]{display:none !important}
</style>
@endverbatim
@endpush

@section('content')
<svg xmlns="http://www.w3.org/2000/svg" class="hidden">
    <defs>
        <g id="i-home"      fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.2 12 3l9 7.2"/><path d="M5.5 9.5V20h13V9.5"/><path d="M9.8 20v-5.2h4.4V20"/></g>
        <g id="i-dashboard" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="2"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="2"/></g>
        <g id="i-learn"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5.5A2.5 2.5 0 0 1 5.5 3H10a2 2 0 0 1 2 2v14a2 2 0 0 0-2-2H3z"/><path d="M21 5.5A2.5 2.5 0 0 0 18.5 3H14a2 2 0 0 0-2 2v14a2 2 0 0 1 2-2h7z"/></g>
        <g id="i-practice"  fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1"/></g>
        <g id="i-results"   fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20h18"/><path d="M5 20V12"/><path d="M10 20V7"/><path d="M15 20v-5"/><path d="M20 20V4"/></g>
        <g id="i-studyplan" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M3 10h18M8 3v4M16 3v4"/></g>
        <g id="i-bookmarks" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 3.5h11a1 1 0 0 1 1 1V21l-6.5-4.2L5.5 21V4.5a1 1 0 0 1 1-1z"/></g>
        <g id="i-notes"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2.5"/><path d="M8.5 8h7M8.5 12h7M8.5 16h4"/></g>
        <g id="i-help"      fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.6 9.2a2.5 2.5 0 1 1 3.3 2.4c-.6.2-.9.8-.9 1.4v.4"/><path d="M12 16.8h.01"/></g>
        <g id="i-settings"  fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-2.7 1.1v.2a2 2 0 1 1-4 0V21a1.6 1.6 0 0 0-2.7-1.2l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A1.6 1.6 0 0 0 3 15.1H2.8a2 2 0 1 1 0-4H3a1.6 1.6 0 0 0 1.2-2.7l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A1.6 1.6 0 0 0 9.8 4.5V4.3a2 2 0 1 1 4 0v.2a1.6 1.6 0 0 0 2.7 1.2l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0 1.1 2.7h.2a2 2 0 1 1 0 4H21a1.6 1.6 0 0 0-1.6 1.1z"/></g>
        <g id="i-bell"      fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8.5a6 6 0 1 0-12 0c0 6-2 7.5-2 7.5h16s-2-1.5-2-7.5"/><path d="M13.7 20a2 2 0 0 1-3.4 0"/></g>
        <g id="i-chevron"   fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></g>
        <g id="i-calendar"  fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M3 10h18M8 3v4M16 3v4"/></g>
        <g id="i-clock"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5.2l3.2 2"/></g>
        <g id="i-download"  fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5v10"/><path d="m8 10 4 4 4-4"/><path d="M4.5 17.5v1.2a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-1.2"/></g>
        <g id="i-refresh"   fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 5.5v5h-5"/><path d="M19.7 10.5A8 8 0 1 0 20 14.6"/></g>
        <g id="i-target"    fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/></g>
        <g id="i-arrow-up"  fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5"/><path d="m5.5 11.5 6.5-6.5 6.5 6.5"/></g>
        <g id="i-arrow-down" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m5.5 12.5 6.5 6.5 6.5-6.5"/></g>
        <g id="i-doc"       fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="m9 14.5 1.8 1.8 3.4-3.6"/></g>
        <g id="i-flask"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 3h5"/><path d="M10.5 3v6L5.6 17.4A2.4 2.4 0 0 0 7.7 21h8.6a2.4 2.4 0 0 0 2.1-3.6L13.5 9V3"/><path d="M8 15h8"/></g>
        <g id="i-leaf"      fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20c0-9 5.5-15 16-15 0 10-5.5 15-13 15H4z"/><path d="M4.5 19.5C8 16 11 13.5 15 11.5"/></g>
        <g id="i-atom"      fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1.8"/><ellipse cx="12" cy="12" rx="9" ry="4" /><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(60 12 12)"/><ellipse cx="12" cy="12" rx="9" ry="4" transform="rotate(120 12 12)"/></g>
        <g id="i-dots"      fill="currentColor"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></g>
        <g id="i-eye"       fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/></g>
        <g id="i-retry"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 5.5v5h-5"/><path d="M19.7 10.5A8 8 0 1 0 20 14.6"/></g>
        <g id="i-share"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5.5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="18.5" r="2.5"/><path d="m8.3 10.8 7.4-4M8.3 13.2l7.4 4"/></g>
        <g id="i-trash"     fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6.5h16"/><path d="M9 6.5V4.8a1.3 1.3 0 0 1 1.3-1.3h3.4A1.3 1.3 0 0 1 15 4.8v1.7"/><path d="M6.5 6.5 7.4 19a2 2 0 0 0 2 1.9h5.2a2 2 0 0 0 2-1.9l.9-12.5"/><path d="M10.5 10.5v6M13.5 10.5v6"/></g>
        <g id="i-check"     fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7"/></g>
        <g id="i-menu"      fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></g>
        <g id="i-empty"     fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><path d="M7.5 10h9M7.5 14h5"/></g>
    </defs>
</svg>

<div class="mb-5 flex flex-wrap items-center gap-4">
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-[13.5px] font-medium text-ink-500">
        <a href="{{ route('dashboard') }}" class="transition hover:text-ink-900">Home</a>
        <span class="text-slate-300">/</span>
        <span class="font-semibold text-brand-600">Mock History</span>
    </nav>

    <button type="button" id="exportBtn"
            class="ml-auto flex h-12 items-center gap-2 rounded-xl border-[1.5px] border-brand-200 px-5 text-[14.5px] font-semibold text-brand-600 transition hover:border-brand-300 hover:bg-brand-50">
        <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]"><use href="#i-download"/></svg>
        Export
    </button>
</div>
{{-- Stats strip --}}
            <section class="mb-6 grid grid-cols-1 gap-6 rounded-2xl bg-slate-50/80 px-7 py-6 sm:grid-cols-3 sm:divide-x sm:divide-slate-200">
                <div class="flex items-center gap-4">
                    <span class="grid h-[52px] w-[52px] shrink-0 place-items-center rounded-2xl bg-brand-100 text-brand-600">
                        <svg viewBox="0 0 24 24" class="h-6 w-6"><use href="#i-calendar"/></svg>
                    </span>
                    <span>
                        <span id="statCompleted" class="block text-[30px] font-extrabold leading-none tracking-tight text-ink-900">{{ $stats['completed'] }}</span>
                        <span class="mt-1.5 block text-[13.5px] font-medium text-ink-500">Tests Completed</span>
                    </span>
                </div>

                <div class="sm:pl-7">
                    <span class="block text-[13px] font-medium text-ink-400">Last Test</span>
                    <span class="mt-1.5 block text-[16px] font-bold text-ink-900">{{ $stats['last_test'] }}</span>
                    <span class="mt-1 block text-[13.5px] text-ink-500">{{ $stats['last_date'] }}</span>
                </div>

                <div class="sm:pl-7">
                    <span class="block text-[13px] font-medium text-ink-400">Score</span>
                    <span class="mt-1.5 flex items-center gap-2.5">
                        <span class="text-[19px] font-extrabold text-ink-900">{{ $stats['score'] }}</span>
                        <span @class([
                            'inline-flex items-center gap-1 text-[13.5px] font-bold',
                            'text-emerald-600' => $stats['delta_positive'],
                            'text-rose-500'    => ! $stats['delta_positive'],
                        ])>
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5"><use href="#i-arrow-{{ $stats['delta_positive'] ? 'up' : 'down' }}"/></svg>
                            {{ abs($stats['delta']) }}%
                        </span>
                    </span>
                    <span class="mt-1 block text-[13.5px] text-ink-500">Improvement from last test</span>
                </div>
            </section>

            {{-- ─────────── Filters ─────────── --}}
            <section class="mb-5 flex flex-wrap items-center gap-3.5">

                {{-- All Exams --}}
                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-trigger
                            class="flex h-[52px] w-[202px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[14.5px] font-medium text-ink-900 transition hover:border-slate-300 data-[on=true]:border-brand-400 data-[on=true]:ring-2 data-[on=true]:ring-brand-100"
                            data-filter-button="exam">
                        <span data-filter-label="exam">All Exams</span>
                        <svg viewBox="0 0 24 24" class="ml-auto h-4 w-4 text-ink-400"><use href="#i-chevron"/></svg>
                    </button>
                    <div data-dropdown-menu hidden class="absolute left-0 top-[58px] z-10 w-[202px] rounded-2xl border border-slate-200 bg-white p-1.5 shadow-pop">
                        @foreach ([['all','All Exams'],['NEET','NEET'],['JEE','JEE']] as [$val,$lbl])
                            <button type="button" data-filter="exam" data-value="{{ $val }}" data-label="{{ $lbl }}"
                                    class="flex w-full items-center rounded-xl px-3 py-2.5 text-left text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                {{ $lbl }}
                                <svg viewBox="0 0 24 24" data-tick class="ml-auto h-4 w-4 text-brand-600" hidden><use href="#i-check"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- All Types --}}
                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-trigger
                            class="flex h-[52px] w-[182px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[14.5px] font-medium text-ink-900 transition hover:border-slate-300 data-[on=true]:border-brand-400 data-[on=true]:ring-2 data-[on=true]:ring-brand-100"
                            data-filter-button="type">
                        <span data-filter-label="type">All Types</span>
                        <svg viewBox="0 0 24 24" class="ml-auto h-4 w-4 text-ink-400"><use href="#i-chevron"/></svg>
                    </button>
                    <div data-dropdown-menu hidden class="absolute left-0 top-[58px] z-10 w-[210px] rounded-2xl border border-slate-200 bg-white p-1.5 shadow-pop">
                        @foreach ([['all','All Types'],['full','Full Length Mock'],['sectional','Sectional Test']] as [$val,$lbl])
                            <button type="button" data-filter="type" data-value="{{ $val }}" data-label="{{ $lbl }}"
                                    class="flex w-full items-center rounded-xl px-3 py-2.5 text-left text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                {{ $lbl }}
                                <svg viewBox="0 0 24 24" data-tick class="ml-auto h-4 w-4 text-brand-600" hidden><use href="#i-check"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- All Time --}}
                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-trigger
                            class="flex h-[52px] w-[232px] items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-4 text-[14.5px] font-medium text-ink-900 transition hover:border-slate-300 data-[on=true]:border-brand-400 data-[on=true]:ring-2 data-[on=true]:ring-brand-100"
                            data-filter-button="range">
                        <svg viewBox="0 0 24 24" class="h-[18px] w-[18px] text-ink-400"><use href="#i-calendar"/></svg>
                        <span data-filter-label="range">All Time</span>
                        <svg viewBox="0 0 24 24" class="ml-auto h-4 w-4 text-ink-400"><use href="#i-chevron"/></svg>
                    </button>
                    <div data-dropdown-menu hidden class="absolute left-0 top-[58px] z-10 w-[232px] rounded-2xl border border-slate-200 bg-white p-1.5 shadow-pop">
                        @foreach ([['all','All Time'],['7','Last 7 days'],['30','Last 30 days'],['90','Last 3 months'],['365','Last 12 months']] as [$val,$lbl])
                            <button type="button" data-filter="range" data-value="{{ $val }}" data-label="{{ $lbl }}"
                                    class="flex w-full items-center rounded-xl px-3 py-2.5 text-left text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                {{ $lbl }}
                                <svg viewBox="0 0 24 24" data-tick class="ml-auto h-4 w-4 text-brand-600" hidden><use href="#i-check"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>

                <button type="button" id="clearFilters"
                        class="ml-auto flex items-center gap-2 rounded-xl px-3 py-2.5 text-[14.5px] font-medium text-ink-500 transition hover:bg-slate-50 hover:text-ink-900">
                    <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]"><use href="#i-refresh"/></svg>
                    Clear Filters
                </button>
            </section>

            {{-- ─────────── Test list ─────────── --}}
            <section id="testList" class="space-y-3">
                @foreach ($tests as $i => $t)
                    @php
                        $icon = match ($t['subject']) {
                            'chemistry' => 'flask',
                            'biology'   => 'leaf',
                            'physics'   => 'atom',
                            default     => 'doc',
                        };
                        $badge = $t['type'] === 'full'
                            ? ['label' => 'Full Length Mock', 'cls' => 'bg-brand-50 text-brand-600']
                            : ['label' => 'Sectional Test',   'cls' => match($t['subject']) {
                                    'chemistry' => 'bg-emerald-50 text-emerald-600',
                                    'biology'   => 'bg-orange-50 text-orange-600',
                                    default     => 'bg-violet-50 text-violet-600',
                              }];
                    @endphp

                    <article class="test-row group relative flex flex-wrap items-center gap-4 rounded-2xl border border-slate-200/90 bg-white px-5 py-[18px] shadow-card transition hover:border-brand-200 hover:shadow-pop lg:flex-nowrap"
                             data-exam="{{ $t['exam'] }}"
                             data-type="{{ $t['type'] }}"
                             data-date="{{ $t['date'] }}"
                             data-title="{{ $t['title'] }}"
                             data-score="{{ $t['score'] }}/{{ $t['total'] }}"
                             data-accuracy="{{ $t['accuracy'] }}%"
                             data-duration="{{ $t['duration'] }}"
                             data-index="{{ $i }}">

                        {{-- icon --}}
                        <span class="grid h-[46px] w-[46px] shrink-0 place-items-center rounded-xl {{ $tile[$t['subject']]['bg'] }} {{ $tile[$t['subject']]['fg'] }}">
                            <svg viewBox="0 0 24 24" class="h-[22px] w-[22px]"><use href="#i-{{ $icon }}"/></svg>
                        </span>

                        {{-- title block --}}
                        <div class="min-w-[220px] flex-1">
                            <h3 class="text-[16.5px] font-bold leading-snug text-ink-900">{{ $t['title'] }}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-2.5">
                                <span class="rounded-md px-2 py-[3px] text-[12px] font-semibold {{ $badge['cls'] }}">{{ $badge['label'] }}</span>
                                <span class="text-[13.5px] text-ink-500">{{ date('j M Y', strtotime($t['date'])) }}</span>
                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                <span class="text-[13.5px] text-ink-500">{{ $t['time'] }}</span>
                            </div>
                        </div>

                        {{-- score --}}
                        <div class="w-[120px] shrink-0">
                            <p class="whitespace-nowrap text-[15px] font-medium text-ink-400">
                                <span class="text-[26px] font-extrabold tracking-tight text-ink-900">{{ $t['score'] }}</span>/{{ $t['total'] }}
                            </p>
                            <p class="mt-0.5 text-[13px] text-ink-500">{{ $t['accuracy'] }}% Accuracy</p>
                        </div>

                        {{-- duration --}}
                        <div class="flex w-[150px] shrink-0 items-center gap-2.5 border-slate-200 lg:border-l lg:pl-5">
                            <svg viewBox="0 0 24 24" class="h-[19px] w-[19px] text-brand-500"><use href="#i-clock"/></svg>
                            <span>
                                <span class="block text-[15px] font-semibold text-ink-900">{{ $t['duration'] }}</span>
                                <span class="block text-[12.5px] text-ink-500">Time Taken</span>
                            </span>
                        </div>

                        {{-- actions --}}
                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('coming-soon', ['feature' => 'review-test']) }}"
                               class="flex h-[42px] items-center rounded-xl border-[1.5px] border-brand-200 px-5 text-[14px] font-semibold text-brand-600 transition hover:border-brand-400 hover:bg-brand-50">
                                Review Test
                            </a>

                            <div class="relative" data-dropdown>
                                <button type="button" data-dropdown-trigger
                                        aria-label="More actions"
                                        class="grid h-10 w-10 place-items-center rounded-xl text-ink-400 transition hover:bg-slate-100 hover:text-ink-900">
                                    <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]"><use href="#i-dots"/></svg>
                                </button>
                                <div data-dropdown-menu hidden
                                     class="absolute right-0 top-[46px] z-20 w-[196px] rounded-2xl border border-slate-200 bg-white p-1.5 shadow-pop">
                                    <a href="{{ route('coming-soon', ['feature' => 'view-solutions']) }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                        <svg viewBox="0 0 24 24" class="h-[17px] w-[17px] text-ink-400"><use href="#i-eye"/></svg> View Solutions
                                    </a>
                                    <a href="{{ route('coming-soon', ['feature' => 're-attempt']) }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                        <svg viewBox="0 0 24 24" class="h-[17px] w-[17px] text-ink-400"><use href="#i-retry"/></svg> Re-attempt
                                    </a>
                                    <button type="button" data-row-export
                                            class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                        <svg viewBox="0 0 24 24" class="h-[17px] w-[17px] text-ink-400"><use href="#i-download"/></svg> Download Report
                                    </button>
                                    <a href="{{ route('coming-soon', ['feature' => 'share-result']) }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[14px] font-medium text-ink-700 hover:bg-slate-50">
                                        <svg viewBox="0 0 24 24" class="h-[17px] w-[17px] text-ink-400"><use href="#i-share"/></svg> Share Result
                                    </a>
                                    <hr class="my-1.5 border-slate-200/80">
                                    <button type="button" data-row-delete
                                            class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-[14px] font-medium text-rose-600 hover:bg-rose-50">
                                        <svg viewBox="0 0 24 24" class="h-[17px] w-[17px]"><use href="#i-trash"/></svg> Delete Attempt
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            {{-- Empty state --}}
            <div id="emptyState" hidden class="mt-3 rounded-2xl border border-dashed border-slate-300 bg-slate-50/60 px-6 py-16 text-center">
                <svg viewBox="0 0 24 24" class="mx-auto h-10 w-10 text-slate-300"><use href="#i-empty"/></svg>
                <p class="mt-3 text-[16px] font-bold text-ink-900">No tests match these filters</p>
                <p class="mt-1 text-[14px] text-ink-500">Try widening the date range or clearing the filters.</p>
                <button type="button" data-clear-proxy
                        class="mt-5 inline-flex h-11 items-center gap-2 rounded-xl bg-brand-600 px-5 text-[14px] font-semibold text-white hover:bg-brand-700">
                    <svg viewBox="0 0 24 24" class="h-4 w-4"><use href="#i-refresh"/></svg> Clear Filters
                </button>
            </div>

            {{-- Load more --}}
            <div id="loadMoreWrap" class="mt-3 rounded-2xl border border-slate-200/90 bg-white shadow-card">
                <button type="button" id="loadMore"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl py-[22px] text-[15px] font-bold text-brand-600 transition hover:bg-brand-50/60">
                    <span id="loadMoreText">Load More</span>
                    <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]"><use href="#i-chevron"/></svg>
                </button>
            </div>

            <p id="resultCount" class="mt-4 text-center text-[13.5px] text-ink-400"></p>
<div id="toastLocal" hidden
     class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-xl bg-ink-900 px-4 py-3 text-[14px] font-medium text-white shadow-pop"></div>
@endsection

@push('scripts')
<script>window.__PER_PAGE = {{ (int) $perPage }};</script>
@verbatim
<script>
(function () {
    'use strict';

    const PER_PAGE = window.__PER_PAGE || 6;
    const state = { exam: 'all', type: 'all', range: 'all', shown: PER_PAGE };

    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    const rows          = $$('.test-row');
    const emptyState    = $('#emptyState');
    const loadMoreWrap  = $('#loadMoreWrap');
    const loadMoreBtn   = $('#loadMore');
    const resultCount   = $('#resultCount');
    const toastEl       = $('#toastLocal');

    /* ────────────────────────── Toast ────────────────────────── */
    let toastTimer;
    function toast(msg) {
        if (!toastEl) { if (window.showToast) window.showToast(msg); return; }
        toastEl.textContent = msg;
        toastEl.hidden = false;
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { toastEl.hidden = true; }, 2600);
    }

    /* ────────────────────── Dropdown engine ──────────────────── */
    function closeAllDropdowns(except) {
        $$('[data-dropdown]').forEach(dd => {
            if (dd === except) return;
            const menu = dd.querySelector('[data-dropdown-menu]');
            if (menu) menu.hidden = true;
            const trg = dd.querySelector('[data-dropdown-trigger]');
            if (trg) trg.setAttribute('aria-expanded', 'false');
        });
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-dropdown-trigger]');
        if (trigger) {
            const dd   = trigger.closest('[data-dropdown]');
            const menu = dd.querySelector('[data-dropdown-menu]');
            const willOpen = menu.hidden;
            closeAllDropdowns(dd);
            menu.hidden = !willOpen;
            trigger.setAttribute('aria-expanded', String(willOpen));
            e.stopPropagation();
            return;
        }
        if (!e.target.closest('[data-dropdown-menu]')) closeAllDropdowns();
    });

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllDropdowns(); });

    /* ─────────────────────── Filtering ───────────────────────── */
    function withinRange(dateStr, days) {
        if (days === 'all') return true;
        const d = new Date(dateStr + 'T00:00:00');
        // Anchor on the newest attempt so demo data stays meaningful.
        const anchor = newestDate();
        const diff = (anchor - d) / 86400000;
        return diff <= Number(days);
    }

    let _newest = null;
    function newestDate() {
        if (_newest) return _newest;
        const times = rows.map(r => new Date(r.dataset.date + 'T00:00:00').getTime());
        _newest = new Date(Math.max.apply(null, times.concat([Date.now()])));
        return _newest;
    }

    function matches(row) {
        if (state.exam !== 'all' && row.dataset.exam !== state.exam) return false;
        if (state.type !== 'all' && row.dataset.type !== state.type) return false;
        if (!withinRange(row.dataset.date, state.range)) return false;
        return true;
    }

    function visibleRows() { return rows.filter(matches); }

    function render(animateFrom) {
        const matched = visibleRows();

        rows.forEach(r => { r.hidden = true; r.classList.remove('row-enter'); });

        matched.slice(0, state.shown).forEach((r, i) => {
            r.hidden = false;
            if (animateFrom !== undefined && i >= animateFrom) r.classList.add('row-enter');
        });

        const showing = Math.min(state.shown, matched.length);
        emptyState.hidden      = matched.length !== 0;
        loadMoreWrap.hidden    = matched.length <= state.shown;
        resultCount.textContent = matched.length
            ? 'Showing ' + showing + ' of ' + matched.length + ' tests'
            : '';

        // keep the filter chips looking "active"
        ['exam', 'type', 'range'].forEach(key => {
            const btn = document.querySelector('[data-filter-button="' + key + '"]');
            if (btn) btn.dataset.on = String(state[key] !== 'all');
        });
    }

    /* filter option clicks */
    $$('[data-filter]').forEach(opt => {
        opt.addEventListener('click', () => {
            const key = opt.dataset.filter;
            state[key]  = opt.dataset.value;
            state.shown = PER_PAGE;

            const label = document.querySelector('[data-filter-label="' + key + '"]');
            if (label) label.textContent = opt.dataset.label;

            // ticks
            $$('[data-filter="' + key + '"]').forEach(o => {
                const tick = o.querySelector('[data-tick]');
                if (tick) tick.hidden = (o !== opt);
            });

            closeAllDropdowns();
            render();
        });
    });

    /* clear filters */
    function clearFilters() {
        state.exam = state.type = state.range = 'all';
        state.shown = PER_PAGE;
        const defaults = { exam: 'All Exams', type: 'All Types', range: 'All Time' };
        Object.keys(defaults).forEach(k => {
            const l = document.querySelector('[data-filter-label="' + k + '"]');
            if (l) l.textContent = defaults[k];
        });
        $$('[data-tick]').forEach(t => { t.hidden = true; });
        render();
        toast('Filters cleared');
    }
    $('#clearFilters').addEventListener('click', clearFilters);
    $$('[data-clear-proxy]').forEach(b => b.addEventListener('click', clearFilters));

    /* load more */
    loadMoreBtn.addEventListener('click', () => {
        const before = state.shown;
        state.shown += PER_PAGE;
        render(before);
    });

    /* ─────────────────────── CSV export ──────────────────────── */
    function downloadCsv(filename, rowsData) {
        const csv = rowsData
            .map(r => r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(','))
            .join('\r\n');
        const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href = url; a.download = filename;
        document.body.appendChild(a); a.click(); a.remove();
        setTimeout(() => URL.revokeObjectURL(url), 1500);
    }

    $('#exportBtn').addEventListener('click', () => {
        const data = [['Test Name', 'Exam', 'Type', 'Date', 'Score', 'Accuracy', 'Time Taken']];
        visibleRows().forEach(r => {
            data.push([
                r.dataset.title, r.dataset.exam,
                r.dataset.type === 'full' ? 'Full Length Mock' : 'Sectional Test',
                r.dataset.date, r.dataset.score, r.dataset.accuracy, r.dataset.duration
            ]);
        });
        downloadCsv('mock-test-history.csv', data);
        toast('Exported ' + (data.length - 1) + ' tests to CSV');
    });

    /* per-row menu actions */
    $$('[data-row-export]').forEach(btn => {
        btn.addEventListener('click', () => {
            const r = btn.closest('.test-row');
            downloadCsv(
                r.dataset.title.replace(/\s+/g, '-').toLowerCase() + '.csv',
                [
                    ['Test Name', 'Exam', 'Date', 'Score', 'Accuracy', 'Time Taken'],
                    [r.dataset.title, r.dataset.exam, r.dataset.date, r.dataset.score, r.dataset.accuracy, r.dataset.duration]
                ]
            );
            closeAllDropdowns();
        });
    });

    $$('[data-row-delete]').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('.test-row');
            const idx = rows.indexOf(row);
            if (idx > -1) rows.splice(idx, 1);
            row.remove();
            closeAllDropdowns();
            render();
            toast('Attempt removed');
            // POST to your backend here, e.g.:
            // fetch('/mock-tests/' + row.dataset.index, { method: 'DELETE',
            //   headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } });
        });
    });

    /* exam switcher in the topbar */
    $$('[data-exam-switch]').forEach(b => {
        b.addEventListener('click', () => {
            const lbl = document.getElementById('testPillLabel');
            if (lbl) lbl.textContent = b.dataset.examSwitch;
            closeAllDropdowns();
            toast('Switched to ' + b.dataset.examSwitch);
        });
    });

    /* sidebar accordion */
    $$('[data-accordion]').forEach(btn => {
        const panel = document.querySelector('[data-accordion-panel="' + btn.dataset.accordion + '"]');
        const caret = btn.querySelector('[data-accordion-caret]');
        if (!panel || !caret) return;
        let open = true;
        btn.addEventListener('click', () => {
            open = !open;
            panel.style.maxHeight = open ? panel.scrollHeight + 'px' : '0px';
            panel.style.opacity   = open ? '1' : '0';
            caret.style.transform = open ? '' : 'rotate(-90deg)';
        });
    });

    /* mobile sidebar + accordion are owned by the shared layout now */

    render();
})();
</script>
@endverbatim
@endpush
