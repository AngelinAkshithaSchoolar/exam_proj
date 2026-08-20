@extends('layouts.app')

@section('title', $title . ' — schoolar.ai')
@section('page-title', $title)
@section('page-sub', 'This section is on the way.')

@php($active = $feature === 'mock-tests' ? 'mock-tests' : '')

@push('styles')
<style>
.page-content .panel{background:var(--card);border:1px solid var(--border);border-radius:18px;
      box-shadow:var(--shadow);padding:48px 44px;max-width:540px;margin:40px auto;text-align:center}
.page-content .panel .icon{font-size:52px;line-height:1;margin-bottom:18px}
.page-content .panel h2{font-size:24px;margin-bottom:10px}
.page-content .pill{display:inline-block;background:#EFEBFF;color:var(--indigo-dark);font-size:11px;
      font-weight:800;letter-spacing:.6px;text-transform:uppercase;padding:5px 11px;
      border-radius:999px;margin-bottom:18px}
.page-content .panel p{color:var(--muted);font-size:14px;line-height:1.65;margin-bottom:26px}
.page-content .actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.page-content .btn{padding:11px 20px;border-radius:10px;font-size:13px;font-weight:700;
      text-decoration:none;border:1px solid var(--border);background:#fff;color:var(--text);
      cursor:pointer;font-family:inherit;transition:.15s}
.page-content .btn:hover{border-color:var(--indigo);color:var(--indigo)}
.page-content .btn.primary{background:var(--indigo);color:#fff;border-color:var(--indigo)}
.page-content .btn.primary:hover{background:var(--indigo-dark);color:#fff}
</style>
@endpush

@section('content')
<div class="panel">
    <div class="icon">{{ $icon }}</div>
    <span class="pill">Coming soon</span>
    <h2>{{ $title }}</h2>
    <p>{{ $blurb }}<br>This section isn't built yet — the rest of the app is fully working, so use the sidebar to jump to a live page.</p>
    <div class="actions">
        <a class="btn primary" href="{{ route('dashboard') }}">← Back to Dashboard</a>
        <a class="btn" href="{{ route('practice.index') }}">Go to Practice</a>
        <a class="btn" href="{{ route('mira.index') }}">Ask Mira</a>
    </div>
</div>
@endsection
