@extends('layouts.app')

@section('title', 'Profile & Settings — schoolar.ai')
@section('page-title', 'Profile & Settings')
@section('page-sub', 'Manage your account details and app preferences.')

@php($active = 'profile')

@push('styles')
<style>
.page-content{max-width:920px}
.page-content .flash{background:#E9FBF3;border:1px solid #B7ECD3;color:#0B7A48;padding:11px 15px;
      border-radius:10px;font-size:13px;margin-bottom:18px}
.page-content .card{background:var(--card);border:1px solid var(--border);border-radius:16px;
      box-shadow:var(--shadow);padding:24px;margin-bottom:18px}
.page-content .card h3{font-size:15px;margin-bottom:16px}
.page-content .who{display:flex;align-items:center;gap:16px;margin-bottom:20px}
.page-content .who img{width:64px;height:64px;border-radius:50%}
.page-content .who b{display:block;font-size:17px}
.page-content .who span{color:var(--muted);font-size:13px}
.page-content .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px}
.page-content .field label{display:block;font-size:11px;font-weight:800;color:var(--muted);
      text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
.page-content .field input,.page-content .field select{width:100%;padding:10px 12px;
      border:1px solid var(--border);border-radius:9px;font-size:13px;font-family:inherit;
      background:#FBFCFE;color:var(--text)}
.page-content .field input:focus,.page-content .field select:focus{outline:none;
      border-color:var(--indigo);background:#fff}
.page-content .row{display:flex;align-items:center;justify-content:space-between;gap:16px;
      padding:13px 0;border-bottom:1px solid var(--border)}
.page-content .row:last-child{border-bottom:none}
.page-content .row b{font-size:13px;display:block}
.page-content .row small{color:var(--muted);font-size:12px}
.page-content .switch{width:40px;height:22px;border-radius:999px;background:#DFE3EE;
      position:relative;cursor:pointer;border:none;flex-shrink:0;transition:.18s}
.page-content .switch::after{content:'';position:absolute;top:3px;left:3px;width:16px;height:16px;
      border-radius:50%;background:#fff;transition:.18s}
.page-content .switch.on{background:var(--green)}
.page-content .switch.on::after{left:21px}
.page-content .actions{display:flex;gap:10px;margin-top:20px;flex-wrap:wrap}
.page-content .btn{padding:11px 20px;border-radius:10px;font-size:13px;font-weight:700;
      text-decoration:none;border:1px solid var(--border);background:#fff;color:var(--text);
      cursor:pointer;font-family:inherit}
.page-content .btn:hover{border-color:var(--indigo);color:var(--indigo)}
.page-content .btn.primary{background:var(--indigo);color:#fff;border-color:var(--indigo)}
.page-content .btn.primary:hover{background:var(--indigo-dark);color:#fff}
.page-content .btn.danger{color:#C42B54;border-color:#F6CBD7}
.page-content .btn.danger:hover{background:#FFECEF;border-color:var(--red)}
</style>
@endpush

@section('content')
@if (session('status'))
    <div class="flash">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="who">
        <img src="https://i.pravatar.cc/160?img=12" alt="Student profile">
        <div><b>Arjun Sharma</b><span>arjun.sharma@gmail.com · PTE Academic</span></div>
    </div>
    <div class="grid">
        <div class="field"><label>Full name</label><input value="Arjun Sharma"></div>
        <div class="field"><label>Email</label><input type="email" value="arjun.sharma@gmail.com"></div>
        <div class="field"><label>Target score</label><input type="number" value="79" min="10" max="90"></div>
        <div class="field"><label>Exam date</label><input type="date" value="2026-10-15"></div>
        <div class="field"><label>Test type</label>
            <select><option>PTE Academic</option><option>PTE Core</option><option>IELTS</option></select>
        </div>
        <div class="field"><label>Time zone</label>
            <select><option>Australia/Sydney</option><option>Asia/Kolkata</option><option>UTC</option></select>
        </div>
    </div>
    <div class="actions">
        <button type="button" class="btn primary" onclick="showToast('Profile saved')">Save changes</button>
        <a class="btn" href="{{ route('dashboard') }}">Cancel</a>
    </div>
</div>

<div class="card">
    <h3>Notifications</h3>
    <div class="row"><div><b>Live class reminders</b><small>15 minutes before a class starts</small></div>
        <button type="button" class="switch on" onclick="flipSwitch(this,'Live class reminders')"></button></div>
    <div class="row"><div><b>Daily practice nudge</b><small>A reminder if you haven't practised by 7pm</small></div>
        <button type="button" class="switch on" onclick="flipSwitch(this,'Daily practice nudge')"></button></div>
    <div class="row"><div><b>Weekly performance report</b><small>Emailed every Monday morning</small></div>
        <button type="button" class="switch" onclick="flipSwitch(this,'Weekly report')"></button></div>
    <div class="row"><div><b>Mira suggestions</b><small>AI tips based on your weakest skills</small></div>
        <button type="button" class="switch on" onclick="flipSwitch(this,'Mira suggestions')"></button></div>
</div>

<div class="card">
    <h3>Session</h3>
    <div class="row">
        <div><b>Demo data</b><small>Practice progress, streaks and bookmarks are stored in your session.</small></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn danger">Log out &amp; reset demo data</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function flipSwitch(el, label) {
    el.classList.toggle('on');
    showToast(label + (el.classList.contains('on') ? ' turned on' : ' turned off'));
}
</script>
@endpush
