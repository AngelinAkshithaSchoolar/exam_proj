@extends('layouts.app')

@section('title', 'Live Classes — schoolar.ai')
@section('page-title', 'Live Classes')
@section('page-sub', "Learn from expert teachers. Interact live and improve faster.")

@php($active = 'live-classes')

@push('styles')
<style>
:root{
    --bg:#F4F6FB; --card:#FFFFFF; --text:#12142B; --muted:#767B94;
    --border:#EBEDF5; --indigo:#5A4CF0; --indigo-dark:#4636D6;
    --green:#12B76A; --red:#F0446E; --orange:#F7931E; --navy:#0E1030;
    --blue:#1677F1; --teal:#08A899;
    --shadow: 0 1px 2px rgba(20,20,50,.04), 0 8px 24px rgba(20,20,50,.05);
    --radius: 16px;
}
.page-content *{box-sizing:border-box;}
.page-content a{color:inherit; text-decoration:none;}
.page-content button{font-family:inherit; cursor:pointer;}
.page-content .live-pill{background:var(--indigo); color:#fff; font-size:11px; font-weight:800; padding:4px 10px; border-radius:8px; vertical-align:middle;}
.page-content .filter-bar{display:flex; align-items:center; gap:10px; margin-bottom:18px; flex-wrap:wrap;}
.page-content .filter-sel{background:#fff; border:1px solid var(--border); border-radius:12px; padding:10px 14px; font-size:13px; font-weight:600; box-shadow:var(--shadow); color:var(--text);}
.page-content .date-wrap{position:relative;}
.page-content .date-wrap input{position:absolute; opacity:0; width:1px; height:1px; pointer-events:none;}
.page-content .search-box{display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--border); padding:10px 14px; border-radius:12px; box-shadow:var(--shadow); flex:1; min-width:180px;}
.page-content .search-box input{border:none; outline:none; font-size:13px; width:100%; background:transparent;}
.page-content .filters-btn{display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--border); padding:10px 14px; border-radius:12px; font-weight:700; font-size:13px; box-shadow:var(--shadow); position:relative;}
.page-content .filters-panel{position:absolute; top:46px; right:0; width:210px; background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 12px 28px rgba(20,20,50,.14); padding:14px; z-index:50; display:none;}
.page-content .filters-panel.open{display:block;}
.page-content .filters-panel label{font-size:11.5px; font-weight:700; color:var(--muted); display:block; margin-bottom:6px;}
.page-content .filters-panel select{width:100%; padding:8px; border-radius:8px; border:1px solid var(--border); font-size:12.5px; margin-bottom:6px;}
.page-content .hero{background:var(--navy); border-radius:var(--radius); overflow:hidden; display:flex; position:relative; margin-bottom:18px;}
.page-content .hero-img{width:230px; flex-shrink:0; position:relative; background-size:cover; background-position:center;}
.page-content .hero-img .badge-live{position:absolute; top:16px; left:16px; background:var(--red); color:#fff; font-size:11px; font-weight:800; padding:5px 10px; border-radius:7px;}
.page-content .hero-body{flex:1; padding:22px 24px; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; color:#fff;}
.page-content .hero-info h2{margin:0 0 4px; font-size:19px; font-weight:800;}
.page-content .hero-info .sub{color:#B9BCE0; font-size:12.5px; margin-bottom:12px;}
.page-content .hero-faces{display:flex; align-items:center; gap:8px; margin-bottom:14px;}
.page-content .hero-faces .stack{display:flex;}
.page-content .hero-faces .stack img{width:26px; height:26px; border-radius:50%; border:2px solid var(--navy); margin-left:-8px; object-fit:cover;}
.page-content .hero-faces .stack img:first-child{margin-left:0;}
.page-content .hero-faces span{font-size:12px; color:#C6C9E8;}
.page-content .hero-tags{display:flex; gap:16px; flex-wrap:wrap;}
.page-content .hero-tags span{font-size:11px; color:#B9BCE0;}
.page-content .hero-timer{background:rgba(255,255,255,.08); border-radius:14px; padding:14px 22px; text-align:center;}
.page-content .hero-timer .lbl{font-size:11px; color:#B9BCE0; margin-bottom:8px;}
.page-content .hero-timer .digits{display:flex; gap:8px; align-items:baseline; font-size:26px; font-weight:900; color:#fff;}
.page-content .hero-timer .digits small{font-size:9px; font-weight:700; color:#8A8DBE; display:block; margin-top:2px;}
.page-content .hero-actions{display:flex; flex-direction:column; align-items:center; gap:10px;}
.page-content .join-hero-btn{background:var(--indigo); color:#fff; border:none; padding:12px 22px; border-radius:11px; font-weight:800; font-size:13.5px; display:flex; align-items:center; gap:8px;}
.page-content .join-hero-btn:disabled{opacity:.5; cursor:not-allowed;}
.page-content .hero-actions a{font-size:12px; color:#B9BCE0; font-weight:700;}
.page-content .tabs{display:flex; gap:4px; border-bottom:1px solid var(--border); margin-bottom:20px; flex-wrap:wrap;}
.page-content .tabs button{border:none; background:transparent; padding:12px 16px; font-weight:700; font-size:13px; color:var(--muted); display:flex; align-items:center; gap:7px; border-bottom:2px solid transparent;}
.page-content .tabs button.active{color:var(--indigo); border-bottom-color:var(--indigo);}
.page-content .tabs button .cnt{background:var(--red); color:#fff; font-size:10px; font-weight:800; padding:1px 6px; border-radius:8px;}
.page-content .section-head{display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;}
.page-content .section-head h3{margin:0; font-size:15px; font-weight:800;}
.page-content .section-head a{font-size:12.5px; font-weight:700; color:var(--indigo);}
.page-content .class-card{display:grid; grid-template-columns:120px 1fr auto auto auto; gap:16px; align-items:center; background:#fff; border:1px solid var(--border); border-radius:14px; padding:14px; box-shadow:var(--shadow); margin-bottom:14px; border-left:4px solid var(--border);}
.page-content .class-card.is-live{border-left-color:var(--red);}
.page-content .class-card.is-upcoming{border-left-color:var(--indigo);}
@media (max-width:1000px){
.page-content .class-card{grid-template-columns:1fr; text-align:left;}
}
.page-content .cc-thumb{position:relative; width:120px; height:76px; border-radius:10px; background-size:cover; background-position:center; flex-shrink:0;}
.page-content .cc-thumb .tag{position:absolute; top:6px; left:6px; font-size:9px; font-weight:800; padding:3px 7px; border-radius:5px; color:#fff;}
.page-content .cc-thumb .tag.live{background:var(--red);}
.page-content .cc-thumb .tag.upcoming{background:var(--indigo);}
.page-content .cc-info h4{margin:0 0 3px; font-size:14px; font-weight:800;}
.page-content .cc-info .sub{font-size:11.5px; color:var(--muted); margin-bottom:8px;}
.page-content .cc-instructor{display:flex; align-items:center; gap:8px;}
.page-content .cc-instructor img{width:22px; height:22px; border-radius:50%; object-fit:cover;}
.page-content .cc-instructor b{font-size:11.5px;}
.page-content .cc-instructor span{display:block; font-size:10px; color:var(--muted);}
.page-content .cc-count{text-align:center;}
.page-content .cc-count b{display:block; font-size:17px; font-weight:900; color:var(--indigo);}
.page-content .cc-count span{font-size:10.5px; color:var(--muted);}
.page-content .cc-time b{display:block; font-size:12px; font-weight:700;}
.page-content .cc-time span{font-size:10.5px; color:var(--muted);}
.page-content .cc-actions{display:flex; flex-direction:column; gap:8px; min-width:150px;}
.page-content .cc-actions .primary-btn{border:none; background:var(--indigo); color:#fff; padding:10px 14px; border-radius:10px; font-weight:800; font-size:12px; display:flex; align-items:center; justify-content:center; gap:6px;}
.page-content .cc-actions .remind-btn{border:1px solid var(--indigo); background:#fff; color:var(--indigo); padding:9px 14px; border-radius:10px; font-weight:800; font-size:12px;}
.page-content .cc-actions .remind-btn.on{background:var(--indigo); color:#fff;}
.page-content .cc-actions .row2{display:flex; gap:8px;}
.page-content .cc-actions .ghost-btn{flex:1; border:1px solid var(--border); background:#fff; color:var(--text); padding:7px 8px; border-radius:8px; font-size:11px; font-weight:600;}
.page-content .cc-actions .ghost-btn.on{color:var(--red); border-color:#FBD3DB; background:#FFF3F5;}
.page-content .view-more{display:block; text-align:center; margin:6px 0 22px; font-weight:700; color:var(--indigo); font-size:13px;}
.page-content .empty-state{text-align:center; padding:40px 10px; color:var(--muted);}
.page-content .enrolled-row{position:relative;}
.page-content .enrolled-track{display:flex; gap:14px; overflow-x:auto; scroll-behavior:smooth; scrollbar-width:none;}
.page-content .enrolled-track::-webkit-scrollbar{display:none;}
.page-content .enroll-card{flex:0 0 260px; background:#fff; border:1px solid var(--border); border-radius:14px; padding:14px; box-shadow:var(--shadow);}
.page-content .enroll-card .thumb{width:100%; height:90px; border-radius:10px; background-size:cover; background-position:center; margin-bottom:10px;}
.page-content .enroll-card b{display:block; font-size:13px; font-weight:800; margin-bottom:3px;}
.page-content .enroll-card .who{font-size:11px; color:var(--muted); margin-bottom:4px;}
.page-content .enroll-card .date{font-size:10.5px; color:var(--muted); margin-bottom:8px;}
.page-content .enroll-tag{display:inline-block; background:#E9FBF3; color:var(--green); font-size:10px; font-weight:800; padding:3px 8px; border-radius:7px; margin-bottom:10px;}
.page-content .enroll-actions{display:flex; gap:8px;}
.page-content .enroll-actions button{flex:1; border-radius:8px; padding:8px; font-size:11.5px; font-weight:700;}
.page-content .enroll-actions .join{border:none; background:var(--indigo); color:#fff;}
.page-content .enroll-actions .join.joined{background:#E9FBF3; color:var(--green);}
.page-content .enroll-actions .details{border:1px solid var(--border); background:#fff; color:var(--text);}
.page-content .carousel-arrow{position:absolute; top:38px; width:32px; height:32px; border-radius:50%; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow); display:flex; align-items:center; justify-content:center; font-size:15px; z-index:5;}
.page-content .carousel-arrow.right{right:-16px;}
.page-content .card{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:20px;}
.page-content .card + .card{margin-top:16px;}
.page-content .week-strip{display:flex; gap:6px; margin-top:10px;}
.page-content .week-day{flex:1; text-align:center; border-radius:12px; padding:10px 4px; cursor:pointer; border:1px solid transparent;}
.page-content .week-day.selected{background:var(--indigo); color:#fff;}
.page-content .week-day .lbl{font-size:10.5px; font-weight:700; margin-bottom:4px; opacity:.8;}
.page-content .week-day .num{font-size:14px; font-weight:900; margin-bottom:5px;}
.page-content .week-day .dot{width:6px; height:6px; border-radius:50%; margin:0 auto;}
.page-content .week-legend{display:flex; gap:12px; flex-wrap:wrap; margin-top:12px; font-size:10px; color:var(--muted);}
.page-content .week-legend span{display:inline-flex; align-items:center; gap:5px;}
.page-content .week-legend i{width:7px; height:7px; border-radius:50%; display:inline-block;}
.page-content .sched-item{display:flex; align-items:center; gap:10px; padding:11px 0; border-bottom:1px solid var(--border);}
.page-content .sched-item:last-child{border-bottom:none;}
.page-content .sched-item .dotcol{display:flex; flex-direction:column; align-items:center; gap:4px; width:16px;}
.page-content .sched-item .dotcol i{width:9px; height:9px; border-radius:50%; background:var(--border);}
.page-content .sched-item.live .dotcol i{background:var(--red);}
.page-content .sched-item .live-tag{background:var(--red); color:#fff; font-size:8.5px; font-weight:800; padding:2px 6px; border-radius:5px; margin-bottom:3px; display:inline-block;}
.page-content .sched-item .txt{flex:1; min-width:0;}
.page-content .sched-item .txt .tm{font-size:10.5px; color:var(--muted); font-weight:700;}
.page-content .sched-item .txt b{display:block; font-size:12px; font-weight:800; margin:2px 0;}
.page-content .sched-item .txt span{font-size:10.5px; color:var(--muted);}
.page-content .sched-btn{border:1px solid var(--indigo); background:#fff; color:var(--indigo); border-radius:8px; padding:7px 10px; font-size:10.5px; font-weight:800; white-space:nowrap;}
.page-content .sched-btn.live-join{background:var(--indigo); color:#fff;}
.page-content .sched-btn.on{background:var(--indigo); color:#fff;}
.page-content .recommend-card{display:flex; gap:12px; align-items:center; background:#F1FBF6; border-radius:14px; padding:14px; margin-top:10px;}
.page-content .recommend-card img{width:48px; height:48px; border-radius:10px; object-fit:cover;}
.page-content .recommend-card .txt{flex:1; min-width:0;}
.page-content .recommend-card b{display:block; font-size:12.5px; font-weight:800;}
.page-content .recommend-card .sub{font-size:10.5px; color:var(--muted); margin:2px 0;}
.page-content .recommend-card .meta{font-size:10px; color:var(--muted);}
.page-content .recommend-card button{border:none; background:var(--green); color:#fff; padding:9px 12px; border-radius:9px; font-weight:800; font-size:11px; white-space:nowrap;}
.page-content .recommend-card button.reserved{background:#DCF8EC; color:#009B62;}
.page-content .recommend-sub{font-size:11px; color:var(--muted); margin-bottom:4px;}
.page-content .recorded-grid{display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-top:10px;}
.page-content .rec-card{cursor:pointer;}
.page-content .rec-thumb{position:relative; width:100%; height:74px; border-radius:10px; background-size:cover; background-position:center; margin-bottom:6px; display:flex; align-items:center; justify-content:center;}
.page-content .rec-thumb .play{width:30px; height:30px; border-radius:50%; background:rgba(255,255,255,.9); display:flex; align-items:center; justify-content:center; font-size:12px; color:var(--indigo);}
.page-content .rec-thumb .dur{position:absolute; bottom:5px; right:6px; background:rgba(0,0,0,.65); color:#fff; font-size:9px; font-weight:700; padding:2px 5px; border-radius:5px;}
.page-content .rec-card b{display:block; font-size:11px; font-weight:700; line-height:1.3; margin-bottom:5px;}
.page-content .rec-bar{height:4px; background:#EEF0F7; border-radius:4px; overflow:hidden;}
.page-content .rec-bar i{display:block; height:100%; background:var(--indigo);}
.page-content .rec-card small{font-size:9.5px; color:var(--muted);}
.page-content .modal-overlay{position:fixed; inset:0; background:rgba(14,16,48,.55); display:none; align-items:center; justify-content:center; z-index:200; padding:20px;}
.page-content .modal-overlay.open{display:flex;}
.page-content .modal{background:#fff; border-radius:20px; width:100%; max-width:460px; padding:24px; position:relative; box-shadow:0 30px 60px rgba(0,0,0,.3); max-height:85vh; overflow:auto;}
.page-content .modal-close{position:absolute; top:16px; right:16px; background:#F1F2FA; border:none; width:32px; height:32px; border-radius:9px; font-size:15px;}
.page-content .modal h2{margin:0 0 4px; font-size:18px; font-weight:800;}
.page-content .modal .sub{color:var(--muted); font-size:12.5px; margin-bottom:16px;}
.page-content .modal p.body-text{font-size:13px; line-height:1.6; color:var(--text);}
.page-content .btn{border:none; padding:11px 18px; border-radius:11px; font-weight:800; font-size:13px;}
.page-content .btn.primary{background:var(--indigo); color:#fff;}
.page-content .hidden{display:none !important;}
.page-content .right-column{width:320px;flex-shrink:0;padding:26px 30px 60px 0;}
.page-content .week-day{background:transparent;}
@media(max-width:1180px){
.page-content .right-column{width:340px;}
}
@media(max-width:820px){
.page-content .right-column{width:100%;padding:0 16px 40px;}
.page-content .hero{flex-direction:column;}
.page-content .hero-img{width:100%;height:180px;}
.page-content .hero-body{padding:18px;}
.page-content .class-card{grid-template-columns:90px minmax(0,1fr);}
.page-content .cc-thumb{width:90px;}
.page-content .cc-count, .page-content .cc-time, .page-content .cc-actions{grid-column:2;}
.page-content .week-strip{overflow-x:auto;}
.page-content .week-day{min-width:48px;}
}
/* right rail restored beside the main column */
.page-content .shell-row{display:flex;align-items:flex-start;gap:22px}
.page-content .shell-main{flex:1;min-width:0}
@media(max-width:1180px){.page-content .shell-row{flex-direction:column}.page-content .shell-row>aside{width:100%!important;padding-right:0!important}}

</style>
@endpush

@section('content')
<div class="shell-row">
<div class="shell-main">
<div class="filter-bar">
            <select class="filter-sel" id="subjectSel" onchange="applyFilters()">
                <option value="">All Subjects</option><option value="speaking">Speaking</option><option value="writing">Writing</option><option value="reading">Reading</option><option value="listening">Listening</option><option value="general">General</option>
            </select>
            <select class="filter-sel" id="levelSel" onchange="applyFilters()">
                <option value="">All Levels</option><option value="beginner">Beginner</option><option value="intermediate">Intermediate</option><option value="advanced">Advanced</option>
            </select>
            <select class="filter-sel" id="teacherSel" onchange="applyFilters()">
                <option value="">All Teachers</option><option value="Pooja Ma'am">Pooja Ma'am</option><option value="Akash Sir">Akash Sir</option><option value="Neha Ma'am">Neha Ma'am</option>
            </select>
            <div class="date-wrap"><button type="button" class="filter-sel" id="dateButton">📅 Date</button><input type="date" id="dateInput"></div>
            <div class="search-box">🔍 <input id="classSearch" placeholder="Search classes..." oninput="applyFilters()"></div>
            <div class="rel">
                <button type="button" class="filters-btn" id="filtersBtn">☰ Filters</button>
                <div class="filters-panel" id="filtersPanel"><label>Sort by</label><select id="sortSel" onchange="applySort()"><option value="default">Default</option><option value="soonest">Soonest first</option><option value="seats">Fewest seats left</option></select></div>
            </div>
        </div>

        <div class="hero">
            <div class="hero-img" id="heroImage"><span class="badge-live">LIVE NOW</span></div>
            <div class="hero-body">
                <div class="hero-info">
                    <h2 id="heroTitle"></h2><div class="sub" id="heroSub"></div>
                    <div class="hero-faces"><div class="stack"><img src="https://i.pravatar.cc/40?img=5" alt=""><img src="https://i.pravatar.cc/40?img=8" alt=""><img src="https://i.pravatar.cc/40?img=9" alt=""></div><span id="learnerCount"></span></div>
                    <div class="hero-tags"><span>◔ Live Q&amp;A</span><span>✦ Interactive</span><span style="cursor:pointer;color:#fff" onclick="openInfoModal(1,'notes')">📝 Notes</span><span>◉ Recording</span></div>
                </div>
                <div class="hero-timer">
                    <div class="lbl">Class ends in</div><div class="digits" id="heroTimer"><span id="tH">00</span>:<span id="tM">38</span>:<span id="tS">24</span></div>
                    <div style="display:flex;gap:26px;margin-top:2px"><small>HR</small><small>MIN</small><small>SEC</small></div>
                </div>
                <div class="hero-actions"><button type="button" class="join-hero-btn" id="heroJoinBtn" onclick="joinClass(1)"></button><a href="#" onclick="openInfoModal(1,'details');return false">Class Details</a></div>
            </div>
        </div>

        <div class="tabs" id="mainTabs">
            <button type="button" class="active" data-tab="all">👤 My Classes</button><button type="button" data-tab="all">⚙ All Classes</button><button type="button" data-tab="upcoming">📅 Upcoming</button><button type="button" data-tab="live">📡 Live Now <span class="cnt" id="liveCount"></span></button><button type="button" data-tab="recorded">▶ Recorded</button><button type="button" data-tab="enrolled">🎓 My Enrollments</button>
        </div>

        <div class="section-head"><h3>Today's Classes</h3><a href="#" onclick="showToast('Full schedule is shown on the right');return false">View Full Schedule →</a></div>
        <div id="classGrid"></div>
        <a href="#" class="view-more" id="viewMoreBtn" onclick="revealMore(event)">View More Classes ⌄</a>

        <div class="section-head" id="enrolledSection"><h3>My Enrolled Classes</h3><a href="#" onclick="showToast('All enrolled classes are displayed below');return false">View All →</a></div>
        <div class="enrolled-row"><div class="enrolled-track" id="enrolledTrack"></div><button type="button" class="carousel-arrow right" onclick="scrollEnrolled()">›</button></div>
</div>
<aside class="right-column">
        <div class="card">
            <div class="section-head"><h3>This Week at a Glance</h3><a href="#" onclick="showToast('Calendar integration coming soon');return false">View Calendar</a></div>
            <div class="week-strip" id="weekStrip"></div>
            <div class="week-legend"><span><i style="background:#F0446E"></i> Live Now</span><span><i style="background:#5A4CF0"></i> Enrolled</span><span><i style="background:#F7931E"></i> Recommended</span><span><i style="background:#C7C9DD"></i> Available</span></div>
        </div>
        <div class="card">
            <div class="section-head"><h3>Today's Schedule</h3><a href="#" onclick="showToast('Select a day above to change the schedule');return false">View Full Schedule</a></div><div id="scheduleList"></div>
        </div>
        <div class="card">
            <div class="section-head"><h3>✳ Recommended by Mira</h3><a href="#" onclick="showToast('More recommendations coming soon');return false">See All</a></div>
            <div class="recommend-sub">Based on your recent Speaking performance</div><div class="recommend-card" id="recommendCard"></div>
        </div>
        <div class="card" id="recordedSection">
            <div class="section-head"><h3>Recorded Library</h3><a href="#" onclick="showToast('Recorded library opened');return false">See All</a></div><div class="recorded-grid" id="recordedGrid"></div>
        </div>
    </aside>
</div>

<div class="modal-overlay" id="infoOverlay"><div class="modal"><button type="button" class="modal-close" onclick="closeModal('infoOverlay')">✕</button><h2 id="infoTitle"></h2><p class="sub" id="infoSub"></p><p class="body-text" id="infoBody"></p></div></div>
<div class="modal-overlay" id="upgradeOverlay"><div class="modal"><button type="button" class="modal-close" onclick="closeModal('upgradeOverlay')">✕</button><h2>👑 Upgrade to Pro</h2><p class="sub">Unlock unlimited live classes, recordings, priority support and downloadable notes.</p><button type="button" class="btn primary" style="width:100%" onclick="showToast('This is a UI demo — no payment is processed.');closeModal('upgradeOverlay')">Continue</button></div></div>
@endsection

@push('scripts')
<script>
'use strict';
const STORAGE_KEY = 'schoolar_liveclasses_blade_v1';
const DEFAULT_STATE = {
    streakCount:12, unreadNotifications:6, currentTest:'PTE Academic', selectedDay:2, showExtras:false,
    classes:[
        {id:1,status:'live',subject:'speaking',level:'intermediate',title:'PTE Speaking Masterclass',sub:'Fluency & Pronunciation',instructor:"Pooja Ma'am",role:'PTE Speaking Expert',img:'https://i.pravatar.cc/100?img=47',countLabel:'324',countType:'Learners Live',time:'05:30 PM - 06:30 PM',duration:'60 min',saved:false,remind:false,joined:false,hiddenExtra:false,desc:'A live, instructor-led session focused on fluency and pronunciation drills for PTE Speaking tasks. Includes live Q&A and a recording sent afterward.'},
        {id:2,status:'upcoming',subject:'writing',level:'advanced',title:'PTE Writing Workshop',sub:'Essay Writing Strategies',instructor:'Akash Sir',role:'PTE 90 Scorer',img:'https://i.pravatar.cc/100?img=13',countLabel:'198',countType:'Seats Left',time:'07:00 PM - 08:00 PM',duration:'60 min',saved:false,remind:false,joined:false,hiddenExtra:false,desc:"Structured breakdown of high-scoring essay templates for 'Discuss Both Views' and 'Argumentative' prompts, with live editing of student drafts."},
        {id:3,status:'upcoming',subject:'reading',level:'beginner',title:'PTE Reading Techniques',sub:'Skimming & Scanning',instructor:"Neha Ma'am",role:'Reading Expert',img:'https://i.pravatar.cc/100?img=32',countLabel:'156',countType:'Seats Left',time:'09:00 PM - 10:00 PM',duration:'60 min',saved:false,remind:false,joined:false,hiddenExtra:false,desc:'Practical skimming and scanning techniques to speed up Reading & Fill in the Blanks tasks without losing accuracy.'},
        {id:4,status:'upcoming',subject:'general',level:'intermediate',title:'Q&A Doubt Clearing Session',sub:'Open floor Q&A',instructor:'Akash Sir',role:'PTE 90 Scorer',img:'https://i.pravatar.cc/100?img=13',countLabel:'87',countType:'Seats Left',time:'08:15 PM - 09:00 PM',duration:'45 min',saved:false,remind:false,joined:false,hiddenExtra:true,desc:'Bring any question from your practice sessions — open floor, first come first served.'}
    ],
    enrolled:[
        {id:101,title:'PTE Writing Workshop',instructor:'Akash Sir',date:'Fri, 30 May • 07:00 PM',img:'https://i.pravatar.cc/100?img=13',joined:false},
        {id:102,title:'PTE Reading Techniques',instructor:"Neha Ma'am",date:'Sat, 31 May • 09:00 PM',img:'https://i.pravatar.cc/100?img=32',joined:false},
        {id:103,title:'Q&A Doubt Clearing',instructor:'Akash Sir',date:'Sun, 1 Jun • 08:00 PM',img:'https://i.pravatar.cc/100?img=13',joined:false}
    ],
    week:[{label:'Mon',num:26,dot:'live'},{label:'Tue',num:27,dot:'live'},{label:'Wed',num:28,dot:'live'},{label:'Thu',num:29,dot:'enrolled'},{label:'Fri',num:30,dot:'enrolled'},{label:'Sat',num:31,dot:'recommended'},{label:'Sun',num:1,dot:'available'}],
    schedule:{
        0:[{id:'d0-1',time:'06:00 PM',title:'PTE Listening Basics',instructor:"Neha Ma'am",live:false,joined:false,remind:false},{id:'d0-2',time:'08:00 PM',title:'Grammar Clinic',instructor:'Akash Sir',live:false,joined:false,remind:false}],
        1:[{id:'d1-1',time:'07:30 PM',title:'Describe Image Deep Dive',instructor:"Pooja Ma'am",live:false,joined:false,remind:false}],
        2:[{id:'d2-1',time:'05:30 PM',title:'PTE Speaking Masterclass',instructor:"Pooja Ma'am",live:true,joined:false,remind:false},{id:'d2-2',time:'07:00 PM',title:'PTE Writing Workshop',instructor:'Akash Sir',live:false,joined:false,remind:false},{id:'d2-3',time:'08:15 PM',title:'Q&A Doubt Clearing Session',instructor:'Akash Sir',live:false,joined:false,remind:false},{id:'d2-4',time:'09:00 PM',title:'PTE Reading Techniques',instructor:"Neha Ma'am",live:false,joined:false,remind:false}],
        3:[{id:'d3-1',time:'06:30 PM',title:'Full Mock Walkthrough',instructor:'Akash Sir',live:false,joined:false,remind:false},{id:'d3-2',time:'08:00 PM',title:'Vocabulary Booster',instructor:"Neha Ma'am",live:false,joined:false,remind:false}],
        4:[{id:'d4-1',time:'07:00 PM',title:'Repeat Sentence Drill',instructor:"Pooja Ma'am",live:false,joined:false,remind:false},{id:'d4-2',time:'08:30 PM',title:'Essay Peer Review',instructor:'Akash Sir',live:false,joined:false,remind:false}],
        5:[{id:'d5-1',time:'05:00 PM',title:'Weekend Mock Test Review',instructor:'Akash Sir',live:false,joined:false,remind:false}],
        6:[{id:'d6-1',time:'04:00 PM',title:'Weekly Recap & Planning',instructor:"Neha Ma'am",live:false,joined:false,remind:false},{id:'d6-2',time:'06:00 PM',title:'Open Practice Room',instructor:"Pooja Ma'am",live:false,joined:false,remind:false}]
    },
    recommended:{title:'PTE Pronunciation Clinic',sub:'Improve clarity & accent',meta:"Today, 7:30 PM • 60 min • Pooja Ma'am",img:'https://i.pravatar.cc/100?img=47',reserved:false},
    recorded:[
        {id:1,title:'Speaking: Describe Image Strategies',duration:'48:12',watched:74,thumb:'https://i.pravatar.cc/200?img=47'},
        {id:2,title:'Essay Writing Masterclass',duration:'1:02:40',watched:65,thumb:'https://i.pravatar.cc/200?img=13'},
        {id:3,title:'Reading Speed Techniques',duration:'36:18',watched:42,thumb:'https://i.pravatar.cc/200?img=32'},
        {id:4,title:'Listening: Note Taking Tips & Tricks',duration:'51:10',watched:80,thumb:'https://i.pravatar.cc/200?img=33'}
    ]
};
const notifications=[{title:'New Sectional Test unlocked',time:'2h ago'},{title:'Your Repeat Sentence accuracy dropped 8%',time:'5h ago'},{title:'Mira suggests a Focus Practice session',time:'1d ago'},{title:'Streak milestone: 12 days in a row 🔥',time:'1d ago'},{title:'Weekly performance report is ready',time:'2d ago'},{title:'Live Class starting soon',time:'3d ago'}];
let state=loadState();
let currentTab='all';
let secondsLeft=38*60+24;
let toastTimer;

function cloneDefaults(){return JSON.parse(JSON.stringify(DEFAULT_STATE));}
function loadState(){try{const saved=JSON.parse(localStorage.getItem(STORAGE_KEY)||'null');return saved?Object.assign(cloneDefaults(),saved):cloneDefaults();}catch{return cloneDefaults();}}
function saveState(){try{localStorage.setItem(STORAGE_KEY,JSON.stringify(state));}catch(error){console.warn('Could not save Live Classes state',error);}}
function esc(value){return String(value).replace(/[&<>"']/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));}
function showToast(message){const toast=document.getElementById('toast');toast.textContent=message;toast.classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>toast.classList.remove('show'),2600);}
function closeModal(id){document.getElementById(id).classList.remove('open');}
function openUpgrade(){document.getElementById('upgradeOverlay').classList.add('open');}

function renderTopbar(){
    document.getElementById('testPillLabel').textContent=state.currentTest;
    document.getElementById('streakChipCount').textContent=state.streakCount;
    const badge=document.getElementById('bellBadge');badge.textContent=state.unreadNotifications;badge.style.display=state.unreadNotifications?'':'none';
    document.getElementById('notificationItems').innerHTML=notifications.map(item=>'<div class="ditem"><b>'+esc(item.title)+'</b><span>'+esc(item.time)+'</span></div>').join('');
}
function renderHero(){
    const item=state.classes.find(entry=>entry.status==='live')||state.classes[0];
    document.getElementById('heroImage').style.backgroundImage='url("'+item.img+'")';
    document.getElementById('heroTitle').textContent=item.title;document.getElementById('heroSub').textContent=item.sub;
    document.getElementById('learnerCount').textContent=item.countLabel+' learners joined';
    document.getElementById('heroJoinBtn').textContent=item.joined?'Joined ✓':'Join Class →';
}
function renderClasses(){
    const grid=document.getElementById('classGrid');
    grid.innerHTML=state.classes.map(item=>{
        const hidden=item.hiddenExtra&&!state.showExtras?' hidden extra-class':' '+(item.hiddenExtra?'extra-class':'');
        const action=item.status==='live'?'<button type="button" class="primary-btn" onclick="joinClass('+item.id+')">'+(item.joined?'Joined ✓':'📡 Join Class')+'</button>':'<button type="button" class="remind-btn '+(item.remind?'on':'')+'" onclick="toggleRemind('+item.id+')">🔔 '+(item.remind?'Reminder Set ✓':'Remind Me')+'</button>';
        return '<div class="class-card '+(item.status==='live'?'is-live':'is-upcoming')+hidden+'" data-status="'+item.status+'" data-subject="'+item.subject+'" data-level="'+item.level+'" data-teacher="'+esc(item.instructor)+'" data-title="'+esc(item.title.toLowerCase())+'" data-time="'+esc(item.time)+'" data-seats="'+Number(item.countLabel)+'">'+
        '<div class="cc-thumb" style="background-image:url(&quot;'+item.img+'&quot;)"><span class="tag '+item.status+'">'+(item.status==='live'?'LIVE':'UPCOMING')+'</span></div><div class="cc-info"><h4>'+esc(item.title)+'</h4><div class="sub">'+esc(item.sub)+'</div><div class="cc-instructor"><img src="'+item.img+'" alt=""><div><b>'+esc(item.instructor)+'</b><span>'+esc(item.role)+'</span></div></div></div><div class="cc-count"><b>'+esc(item.countLabel)+'</b><span>'+esc(item.countType)+'</span></div><div class="cc-time"><b>'+esc(item.time)+'</b><span>'+esc(item.duration)+'</span></div><div class="cc-actions">'+action+'<div class="row2"><button type="button" class="ghost-btn '+(item.saved?'on':'')+'" onclick="toggleSave('+item.id+')">'+(item.saved?'♥ Saved':'♡ Save')+'</button><button type="button" class="ghost-btn" onclick="openInfoModal('+item.id+',\''+(item.status==='live'?'notes':'details')+'\')">'+(item.status==='live'?'📝 Notes':'ⓘ Details')+'</button></div></div></div>';
    }).join('');
    document.getElementById('viewMoreBtn').style.display=state.showExtras?'none':'';
    document.getElementById('liveCount').textContent=state.classes.filter(item=>item.status==='live').length;
    applyFilters();
}
function renderEnrolled(){
    document.getElementById('enrolledTrack').innerHTML=state.enrolled.map(item=>'<div class="enroll-card"><div class="thumb" style="background-image:url(&quot;'+item.img+'&quot;)"></div><b>'+esc(item.title)+'</b><div class="who">'+esc(item.instructor)+'</div><div class="date">'+esc(item.date)+'</div><div class="enroll-tag">Enrolled</div><div class="enroll-actions"><button type="button" class="join '+(item.joined?'joined':'')+'" onclick="enrollJoin('+item.id+')">'+(item.joined?'Joined ✓':'Join')+'</button><button type="button" class="details" onclick="showToast(\'Class details coming soon\')">Details</button></div></div>').join('');
}
function renderWeek(){
    const colors={live:'#F0446E',enrolled:'#5A4CF0',recommended:'#F7931E',available:'#C7C9DD'};
    document.getElementById('weekStrip').innerHTML=state.week.map((day,index)=>'<button type="button" class="week-day '+(index===state.selectedDay?'selected':'')+'" onclick="selectDay('+index+')"><div class="lbl">'+day.label+'</div><div class="num">'+day.num+'</div><div class="dot" style="background:'+(index===state.selectedDay?'#fff':colors[day.dot])+'"></div></button>').join('');
}
function renderSchedule(){
    const items=state.schedule[state.selectedDay]||[];
    document.getElementById('scheduleList').innerHTML=items.length?items.map(item=>'<div class="sched-item '+(item.live?'live':'')+'"><div class="dotcol"><i></i></div><div class="txt">'+(item.live?'<span class="live-tag">LIVE</span>':'')+'<div class="tm">'+esc(item.time)+'</div><b>'+esc(item.title)+'</b><span>'+esc(item.instructor)+'</span></div><button type="button" class="sched-btn '+(item.live?'live-join ':'')+(item.remind?'on':'')+'" onclick="scheduleAction(\''+item.id+'\',\''+(item.live?'join':'remind')+'\')">'+(item.live?(item.joined?'Joined ✓':'📡 Join'):'🔔 '+(item.remind?'Set ✓':'Remind Me'))+'</button></div>').join(''):'<div class="nav-empty">No classes scheduled</div>';
}
function renderRecommended(){
    const item=state.recommended;document.getElementById('recommendCard').innerHTML='<img src="'+item.img+'" alt=""><div class="txt"><b>'+esc(item.title)+'</b><div class="sub">'+esc(item.sub)+'</div><div class="meta">'+esc(item.meta)+'</div></div><button type="button" class="'+(item.reserved?'reserved':'')+'" onclick="reserveSeat()">'+(item.reserved?'Reserved ✓':'Reserve Seat')+'</button>';
}
function renderRecorded(){
    document.getElementById('recordedGrid').innerHTML=state.recorded.map(item=>'<button type="button" class="rec-card" onclick="playRecorded('+item.id+')" style="border:0;background:transparent;text-align:left;padding:0"><div class="rec-thumb" style="background-image:url(&quot;'+item.thumb+'&quot;)"><div class="play">▶</div><div class="dur">'+esc(item.duration)+'</div></div><b>'+esc(item.title)+'</b><div class="rec-bar"><i style="width:'+item.watched+'%"></i></div><small class="watched-lbl">'+item.watched+'% watched</small></button>').join('');
}
function renderAll(){renderTopbar();renderHero();renderClasses();renderEnrolled();renderWeek();renderSchedule();renderRecommended();renderRecorded();}

function switchTest(name){state.currentTest=name;saveState();renderTopbar();document.getElementById('testPillDropdown').classList.remove('open');showToast('Switched to '+name);}
function markAllRead(event){event.preventDefault();state.unreadNotifications=0;saveState();renderTopbar();showToast('All notifications marked as read');}
function resetPage(event){event.preventDefault();if(confirm('Reset all Live Classes data back to defaults?')){localStorage.removeItem(STORAGE_KEY);location.reload();}}
function joinClass(id){const item=state.classes.find(entry=>entry.id===id);if(!item)return;item.joined=true;saveState();renderHero();renderClasses();showToast('You joined the class!');}
function toggleSave(id){const item=state.classes.find(entry=>entry.id===id);if(!item)return;item.saved=!item.saved;saveState();renderClasses();showToast(item.saved?'Saved to your list':'Removed from saved');}
function toggleRemind(id){const item=state.classes.find(entry=>entry.id===id);if(!item)return;item.remind=!item.remind;saveState();renderClasses();showToast(item.remind?"We'll remind you before it starts":'Reminder removed');}
function revealMore(event){event.preventDefault();state.showExtras=true;saveState();renderClasses();}
function openInfoModal(id,type){const item=state.classes.find(entry=>entry.id===id);if(!item)return;document.getElementById('infoTitle').textContent=(type==='notes'?'📝 Notes — ':'ⓘ ')+item.title;document.getElementById('infoSub').textContent=item.sub;document.getElementById('infoBody').textContent=type==='notes'?'No notes added yet for this class. Notes shared by the instructor will appear here after the session.':item.desc;document.getElementById('infoOverlay').classList.add('open');}
function selectDay(index){state.selectedDay=index;saveState();renderWeek();renderSchedule();}
function scheduleAction(id,type){const item=(state.schedule[state.selectedDay]||[]).find(entry=>entry.id===id);if(!item)return;if(type==='join')item.joined=true;else item.remind=!item.remind;saveState();renderSchedule();showToast(type==='join'?'Joined the class':item.remind?'Reminder set':'Reminder removed');}
function reserveSeat(){state.recommended.reserved=true;saveState();renderRecommended();showToast('Seat reserved for PTE Pronunciation Clinic');}
function playRecorded(id){const item=state.recorded.find(entry=>entry.id===id);if(!item)return;item.watched=Math.min(100,item.watched+5);saveState();renderRecorded();showToast('Playing "'+item.title+'"...');}
function scrollEnrolled(){document.getElementById('enrolledTrack').scrollBy({left:280,behavior:'smooth'});}
function enrollJoin(id){const item=state.enrolled.find(entry=>entry.id===id);if(!item||item.joined)return;item.joined=true;saveState();renderEnrolled();showToast('Joined the class');}

function applyFilters(){
    const subject=document.getElementById('subjectSel').value,level=document.getElementById('levelSel').value,teacher=document.getElementById('teacherSel').value,query=document.getElementById('classSearch').value.trim().toLowerCase();
    document.querySelectorAll('#classGrid .class-card').forEach(card=>{const tabOk=currentTab==='all'||card.dataset.status===currentTab;const visible=tabOk&&(!subject||card.dataset.subject===subject)&&(!level||card.dataset.level===level)&&(!teacher||card.dataset.teacher===teacher)&&(!query||card.dataset.title.includes(query));if(card.classList.contains('extra-class')&&!state.showExtras)card.style.display='none';else card.style.display=visible?'':'none';});
}
function applySort(){const value=document.getElementById('sortSel').value,grid=document.getElementById('classGrid'),cards=Array.from(grid.children);if(value==='seats')cards.sort((a,b)=>(Number(a.dataset.seats)||9999)-(Number(b.dataset.seats)||9999));else if(value==='soonest')cards.sort((a,b)=>a.dataset.time.localeCompare(b.dataset.time));cards.forEach(card=>grid.appendChild(card));document.getElementById('filtersPanel').classList.remove('open');}
function tickTimer(){if(secondsLeft<=0){document.getElementById('heroTimer').textContent='Class ended';document.getElementById('heroJoinBtn').disabled=true;return;}secondsLeft--;const hours=Math.floor(secondsLeft/3600),minutes=Math.floor((secondsLeft%3600)/60),seconds=secondsLeft%60;document.getElementById('tH').textContent=String(hours).padStart(2,'0');document.getElementById('tM').textContent=String(minutes).padStart(2,'0');document.getElementById('tS').textContent=String(seconds).padStart(2,'0');}

document.getElementById('mainTabs').addEventListener('click',event=>{const button=event.target.closest('button[data-tab]');if(!button)return;document.querySelectorAll('#mainTabs button').forEach(item=>item.classList.remove('active'));button.classList.add('active');currentTab=button.dataset.tab;if(currentTab==='enrolled'){currentTab='all';document.getElementById('enrolledSection').scrollIntoView({behavior:'smooth'});return;}if(currentTab==='recorded'){currentTab='all';document.getElementById('recordedSection').scrollIntoView({behavior:'smooth'});return;}applyFilters();});
document.getElementById('bellBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('bellDropdown').classList.toggle('open');});
document.getElementById('avatarBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('profileDropdown').classList.toggle('open');});
document.getElementById('profileBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('profileDropdown').classList.toggle('open');});
document.getElementById('testPillBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('testPillDropdown').classList.toggle('open');});
document.getElementById('filtersBtn').addEventListener('click',()=>document.getElementById('filtersPanel').classList.toggle('open'));
document.getElementById('dateButton').addEventListener('click',()=>{const input=document.getElementById('dateInput');if(input.showPicker)input.showPicker();else input.click();});
document.addEventListener('click',event=>{document.querySelectorAll('.dropdown.open').forEach(dropdown=>{if(!dropdown.parentElement.contains(event.target))dropdown.classList.remove('open');});const panel=document.getElementById('filtersPanel');if(panel.classList.contains('open')&&!document.getElementById('filtersBtn').contains(event.target)&&!panel.contains(event.target))panel.classList.remove('open');if(window.innerWidth<=1180&&!document.getElementById('sidebar').contains(event.target))document.getElementById('sidebar').classList.remove('open');});
document.querySelectorAll('.modal-overlay').forEach(overlay=>overlay.addEventListener('click',event=>{if(event.target===overlay)overlay.classList.remove('open');}));

renderAll();setInterval(tickTimer,1000);
</script>
@endpush