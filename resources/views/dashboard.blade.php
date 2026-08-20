@extends('layouts.app')

@section('title', 'Dashboard — schoolar.ai')
@section('page-title', 'Good Morning, Arjun! 👋')
@section('page-sub', "Let's make today a step closer to your target score.")

@php($active = 'dashboard')

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
.page-content .layout{display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:20px; align-items:start;}
@media (max-width:1180px){
.page-content .layout{grid-template-columns:1fr;}
}
.page-content .card{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:20px;}
.page-content .card + .card{margin-top:18px;}
.page-content .card-head{display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;}
.page-content .card-head h3{margin:0; font-size:15px; font-weight:800;}
.page-content .card-head a{font-size:12.5px; font-weight:700; color:var(--indigo);}
.page-content .hero{background:linear-gradient(120deg,#6425E4,#1555ED 62%,#0090EA); border-radius:var(--radius); color:#fff; padding:26px 30px; display:flex; align-items:center; gap:34px; position:relative; overflow:hidden; flex-wrap:wrap;}
.page-content .hero:after{content:'🚀'; position:absolute; right:26px; top:20px; font-size:52px; transform:rotate(-18deg); opacity:.95;}
.page-content .hero-block{position:relative;}
.page-content .hero-block .eyebrow{font-size:11px; font-weight:800; letter-spacing:.04em; opacity:.85;}
.page-content .hero-block h2{margin:8px 0 14px; font-size:21px; font-weight:800;}
.page-content .hero-block .test-switch{position:relative;}
.page-content .hero-block .test-switch>button{background:#fff; color:var(--indigo-dark); border:none; height:38px; padding:0 16px; border-radius:9px; font-weight:800; font-size:13px; display:flex; align-items:center; gap:8px;}
.page-content .hero-metric{position:relative; text-align:left;}
.page-content .hero-metric .lbl{font-size:11.5px; opacity:.85; margin-bottom:8px;}
.page-content .hero-ring{position:relative; width:88px; height:88px;}
.page-content .hero-ring svg{transform:rotate(-90deg);}
.page-content .hero-ring .val{position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;}
.page-content .hero-ring .val b{font-size:24px; font-weight:900;}
.page-content .hero-ring .val span{font-size:10.5px; opacity:.85;}
.page-content .hero-metric strong{font-size:25px; font-weight:900;}
.page-content .hero-metric strong sup{font-size:12px; font-weight:700;}
.page-content .hero-improve{position:relative; font-weight:800; font-size:13.5px; max-width:210px;}
.page-content .hero-improve small{display:block; font-weight:500; font-size:11.5px; opacity:.9; margin-top:8px; line-height:1.5;}
.page-content .skill-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin:18px 0;}
@media (max-width:900px){
.page-content .skill-grid{grid-template-columns:repeat(2,1fr);}
}
.page-content .skill-card{background:#fff; border:1px solid var(--border); border-radius:16px; padding:16px; box-shadow:var(--shadow);}
.page-content .skill-top{display:flex; align-items:center; gap:10px; font-weight:800; font-size:13px; margin-bottom:12px;}
.page-content .skill-top .ic{width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px;}
.page-content .skill-top .chev{margin-left:auto; color:#B5B9D6; font-size:16px;}
.page-content .skill-body{display:flex; align-items:center; gap:14px;}
.page-content .ring{width:69px; height:69px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;}
.page-content .ring .hole{width:57px; height:57px; background:#fff; border-radius:50%; display:flex; flex-direction:column; align-items:center; justify-content:center;}
.page-content .ring .hole b{font-size:18px; font-weight:900; line-height:1;}
.page-content .ring .hole span{font-size:9px; color:var(--muted); margin-top:1px;}
.page-content .skill-status b{display:block; font-size:12.5px; font-weight:800; color:var(--green); margin-bottom:6px;}
.page-content .skill-status small{font-size:11px; color:var(--muted);}
.page-content .lower{display:grid; grid-template-columns:1.15fr .85fr 1.05fr; gap:16px; margin-top:18px;}
@media (max-width:1180px){
.page-content .lower{grid-template-columns:1fr;}
}
.page-content .lower .card{margin-top:0; min-height:330px; display:flex; flex-direction:column;}
.page-content .featured-item{background:#F6F7FF; border-radius:14px; padding:14px; display:flex; gap:12px; align-items:center;}
.page-content .featured-item .ic{width:46px; height:46px; border-radius:11px; background:#E9E4FF; display:flex; align-items:center; justify-content:center; font-size:19px; flex-shrink:0;}
.page-content .featured-item .tag{font-size:10.5px; color:var(--muted); font-weight:700;}
.page-content .featured-item h4{margin:3px 0 8px; font-size:14px; font-weight:800;}
.page-content .featured-item .arrow{margin-left:auto; color:var(--muted); font-size:18px;}
.page-content .mini-bar{height:5px; background:#E3E5F8; border-radius:99px; width:160px; overflow:hidden;}
.page-content .mini-bar i{display:block; height:100%; background:var(--indigo); border-radius:inherit;}
.page-content .featured-item small{display:block; margin-top:6px; font-size:11px; color:var(--muted);}
.page-content .learn-item{display:flex; align-items:center; gap:11px; padding:13px 0 0;}
.page-content .learn-item .bubble{width:26px; height:26px; border-radius:50%; background:#F1EFFF; color:var(--indigo); display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0;}
.page-content .learn-item .txt{flex:1; min-width:0;}
.page-content .learn-item .txt b{display:block; font-size:12.5px; font-weight:700;}
.page-content .learn-item .txt span{font-size:11px; color:var(--muted);}
.page-content .learn-item .pctwrap{text-align:right; font-size:11px; font-weight:700; color:var(--muted);}
.page-content .learn-item .mini-bar{width:88px; margin-top:4px;}
.page-content .learn-item .chev{color:var(--muted); font-size:16px; margin-left:4px;}
.page-content .tutor-badge{background:#EEE9FF; color:var(--indigo); border-radius:10px; padding:2px 9px; font-size:9.5px; font-weight:800;}
.page-content .tutor-greet{display:flex; gap:10px; margin-bottom:12px;}
.page-content .tutor-greet .bot{font-size:30px; flex-shrink:0;}
.page-content .tutor-greet h4{margin:2px 0 4px; font-size:13.5px; font-weight:800;}
.page-content .tutor-greet p{margin:0; font-size:11.5px; color:var(--muted); line-height:1.5;}
.page-content .chat-log{flex:1; overflow-y:auto; max-height:150px; display:flex; flex-direction:column; gap:8px; margin-bottom:10px; padding-right:2px;}
.page-content .chat-bubble{font-size:12px; line-height:1.5; padding:9px 11px; border-radius:11px; max-width:92%;}
.page-content .chat-bubble.user{align-self:flex-end; background:var(--indigo); color:#fff; border-bottom-right-radius:3px;}
.page-content .chat-bubble.bot{align-self:flex-start; background:#F1F2FA; color:var(--text); border-bottom-left-radius:3px;}
.page-content .quick{display:flex; flex-direction:column; gap:7px; margin-top:auto;}
.page-content .quick button{text-align:left; padding:8px 12px; color:var(--indigo); background:#fff; border:1px solid var(--border); border-radius:18px; font-size:11.5px; font-weight:600;}
.page-content .quick button:hover{background:#F6F5FF;}
.page-content .ask-row{margin-top:12px; display:flex; align-items:center; gap:8px; border:1px solid var(--border); border-radius:10px; padding:6px 6px 6px 12px;}
.page-content .ask-row input{flex:1; border:none; outline:none; font-size:12.5px; background:transparent;}
.page-content .ask-row button{width:30px; height:30px; border-radius:8px; background:var(--indigo); color:#fff; border:none; font-size:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0;}
.page-content .perf-score{font-size:26px; font-weight:900; margin:4px 0 2px;}
.page-content .perf-score sup{font-size:12px; font-weight:700; color:var(--muted);}
.page-content .perf-badge{font-size:10px; font-weight:800; background:#DCF8EC; color:#009B62; border-radius:9px; padding:2px 8px; margin-left:6px;}
.page-content .perf-sub{font-size:11px; color:var(--muted); margin-bottom:6px;}
.page-content .trend-svg{width:100%; height:110px;}
.page-content .trend-labels{display:flex; justify-content:space-between; font-size:9.5px; color:var(--muted); margin-top:2px;}
.page-content .breakdown{margin-top:14px; border-top:1px solid var(--border); padding-top:14px; font-size:10.5px; font-weight:800;}
.page-content .breakdown-top{display:flex; justify-content:space-between; margin-bottom:6px;}
.page-content .radar-wrap{position:relative; width:150px; height:110px; margin:6px auto 0;}
.page-content .diamond{position:absolute; left:50%; top:50%; width:64px; height:64px; background:#E9E0FF; border:1.5px solid var(--indigo); transform:translate(-50%,-50%) rotate(45deg);}
.page-content .radar-lbl{position:absolute; font-size:10px; font-weight:800; color:var(--text); text-align:center;}
.page-content .radar-lbl small{display:block; font-weight:600; color:var(--muted); font-size:9.5px;}
.page-content .tests-panel{margin-top:18px;}
.page-content .carousel-row{position:relative;}
.page-content .carousel-track{display:flex; gap:12px; overflow-x:auto; scroll-behavior:smooth; padding-bottom:4px; scrollbar-width:none;}
.page-content .carousel-track::-webkit-scrollbar{display:none;}
.page-content .test-card{flex:0 0 170px; border:1px solid var(--border); border-radius:12px; padding:16px 14px; text-align:left; position:relative;}
.page-content .test-card .badge-new{position:absolute; top:10px; right:10px; background:var(--indigo); color:#fff; font-size:9px; font-weight:800; padding:3px 7px; border-radius:8px;}
.page-content .test-card strong{display:block; font-size:15px; margin-bottom:10px;}
.page-content .test-card p{margin:0 0 12px; font-size:11px; color:var(--muted);}
.page-content .test-card a{color:var(--indigo); font-size:11.5px; font-weight:800;}
.page-content .carousel-arrow{position:absolute; top:38px; width:30px; height:30px; border-radius:50%; background:#fff; border:1px solid var(--border); box-shadow:var(--shadow); display:flex; align-items:center; justify-content:center; font-size:14px; z-index:5;}
.page-content .carousel-arrow.left{left:-14px;}
.page-content .carousel-arrow.right{right:-14px;}
.page-content .plan-card{padding:20px;}
.page-content .plan-ring-wrap{display:flex; align-items:center; gap:14px; margin-bottom:2px;}
.page-content .plan-ring{width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;}
.page-content .plan-ring .hole{width:45px; height:45px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:13px;}
.page-content .plan-sub{font-size:11.5px; color:var(--muted); font-weight:700;}
.page-content .tasks{display:flex; flex-direction:column; gap:13px; margin:16px 0 4px;}
.page-content .task-row{display:flex; align-items:flex-start; gap:12px; font-size:12px; color:var(--text); cursor:pointer; user-select:none;}
.page-content .task-row .dot{width:18px; height:18px; border-radius:50%; border:2px solid var(--border); flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:11px; color:#fff; margin-top:1px;}
.page-content .task-row.done .dot{background:var(--green); border-color:var(--green);}
.page-content .task-row .lbl b{display:block; font-weight:700; line-height:1.4;}
.page-content .task-row .lbl small{color:var(--muted); font-size:10.5px;}
.page-content .task-row.done .lbl b{color:#4B5384;}
.page-content .continue-btn{width:100%; height:40px; border-radius:10px; border:1px solid #D9D0FF; background:#fff; color:var(--indigo-dark); font-weight:800; font-size:13px; margin-top:14px;}
.page-content .class-row{display:flex; align-items:center; gap:12px; margin-top:14px;}
.page-content .class-row img{width:56px; height:56px; border-radius:10px; object-fit:cover; flex-shrink:0;}
.page-content .class-row .txt{flex:1; min-width:0;}
.page-content .class-row .txt .liverow{display:flex; align-items:center; gap:6px; margin-bottom:3px;}
.page-content .live-tag{background:var(--indigo); color:#fff; font-size:8.5px; font-weight:800; padding:2px 6px; border-radius:5px;}
.page-content .class-row .txt b{font-size:12px; font-weight:800;}
.page-content .class-row .txt span{display:block; font-size:10.5px; color:var(--muted); margin-top:2px;}
.page-content .join-btn{border:1px solid var(--border); background:#F6F5FF; color:var(--indigo); border-radius:8px; padding:7px 14px; font-size:11px; font-weight:800; flex-shrink:0;}
.page-content .join-btn.joined{background:#E9FBF3; color:var(--green); border-color:#C9F3DF;}
.page-content .announcement{background:linear-gradient(110deg,#FFF0F3,#E7DFFF); border-radius:14px; padding:16px 16px 16px 90px; position:relative; font-size:11px;}
.page-content .announcement:before{content:'🏆'; font-size:44px; position:absolute; left:16px; top:20px;}
.page-content .announcement b{font-size:12.5px;}
.page-content .announcement p{margin:6px 0 10px; color:#4B5384;}
.page-content .announcement a{color:var(--indigo-dark); font-weight:800; font-size:11px;}
.page-content .motivation{background:linear-gradient(120deg,#7457D9,#3725A4); color:#fff; border-radius:14px; padding:16px 18px 14px 46px; font-size:11.5px; position:relative;}
.page-content .motivation:before{content:'"'; position:absolute; left:16px; top:8px; font-size:30px; opacity:.7;}
.page-content .motivation small{opacity:.85;}
.page-content .motivation b{display:block; font-size:13px; margin-top:6px; line-height:1.4;}
.page-content .modal-overlay{position:fixed; inset:0; background:rgba(14,16,48,.55); display:none; align-items:center; justify-content:center; z-index:200; padding:20px;}
.page-content .modal-overlay.open{display:flex;}
.page-content .modal{background:#fff; border-radius:20px; width:100%; max-width:420px; padding:24px; position:relative; box-shadow:0 30px 60px rgba(0,0,0,.3);}
.page-content .modal-close{position:absolute; top:16px; right:16px; background:#F1F2FA; border:none; width:32px; height:32px; border-radius:9px; font-size:15px;}
.page-content .modal h2{margin:0 0 4px; font-size:18px; font-weight:800;}
.page-content .modal .sub{color:var(--muted); font-size:12.5px; margin-bottom:16px;}
.page-content .btn{border:none; padding:11px 18px; border-radius:11px; font-weight:800; font-size:13px;}
.page-content .btn.primary{background:var(--indigo); color:#fff;}
.page-content .hidden{display:none !important;}
.page-content .layout{min-height:0;display:grid;grid-template-columns:minmax(0,1fr) 286px;gap:8px;align-items:stretch;}
.page-content .dashboard-left{min-width:0;min-height:0;display:grid;grid-template-rows:100px 108px minmax(0,1fr) 103px;gap:8px;overflow:hidden;}
.page-content .dashboard-right{min-width:0;min-height:0;display:grid;grid-template-rows:minmax(260px,1.55fr) minmax(128px,.85fr) minmax(88px,.55fr) 48px;gap:8px;overflow:hidden;}
.page-content .card{padding:10px;border-radius:12px;min-height:0;overflow:hidden;}
.page-content .card + .card{margin-top:0;}
.page-content .card-head{margin-bottom:7px;}
.page-content .card-head h3{font-size:12px;}
.page-content .card-head a{font-size:10px;}
.page-content .hero{height:100%;padding:10px 14px;gap:18px;flex-wrap:nowrap;border-radius:12px;}
.page-content .hero:after{font-size:34px;right:12px;top:8px;}
.page-content .hero-block{min-width:170px;}
.page-content .hero-block h2{font-size:16px;margin:3px 0 7px;}
.page-content .hero-block .test-switch>button{height:29px;padding:0 10px;font-size:10px;}
.page-content .hero-metric .lbl{font-size:9px;margin-bottom:3px;}
.page-content .hero-ring{width:60px;height:60px;}
.page-content .hero-ring svg{width:60px;height:60px;}
.page-content .hero-ring .val b{font-size:17px;}
.page-content .hero-metric strong{font-size:19px;}
.page-content .hero-improve{font-size:11px;max-width:170px;}
.page-content .hero-improve small{font-size:9px;margin-top:3px;line-height:1.3;}
.page-content .skill-grid{height:100%;margin:0;gap:8px;grid-template-columns:repeat(4,minmax(0,1fr));}
.page-content .skill-card{padding:9px;border-radius:12px;overflow:hidden;}
.page-content .skill-top{font-size:10.5px;margin-bottom:5px;gap:6px;}
.page-content .skill-top .ic{width:26px;height:26px;font-size:12px;}
.page-content .skill-body{gap:7px;}
.page-content .ring{width:52px;height:52px;}
.page-content .ring .hole{width:43px;height:43px;}
.page-content .ring .hole b{font-size:14px;}
.page-content .skill-status b{font-size:10px;margin-bottom:2px;}
.page-content .skill-status small{font-size:8.5px;line-height:1.25;display:block;}
.page-content .lower{height:100%;min-height:0;margin:0;gap:8px;grid-template-columns:1.15fr .85fr 1.05fr;}
.page-content .lower .card{min-height:0;height:100%;padding:9px;}
.page-content .featured-item{padding:8px;gap:7px;border-radius:10px;}
.page-content .featured-item .ic{width:32px;height:32px;font-size:14px;}
.page-content .featured-item .tag{font-size:8px;}
.page-content .featured-item h4{font-size:10.5px;margin:1px 0 4px;}
.page-content .mini-bar{width:105px;height:4px;}
.page-content .featured-item small{font-size:8px;margin-top:3px;}
.page-content .learn-item{padding-top:7px;gap:6px;}
.page-content .learn-item .bubble{width:21px;height:21px;font-size:10px;}
.page-content .learn-item .txt b{font-size:9.5px;}
.page-content .learn-item .txt span{font-size:8px;}
.page-content .learn-item .pctwrap{font-size:8px;}
.page-content .learn-item .mini-bar{width:54px;}
.page-content .tutor-greet{margin-bottom:5px;gap:5px;}
.page-content .tutor-greet .bot{font-size:21px;}
.page-content .tutor-greet h4{font-size:10px;margin:0;}
.page-content .tutor-greet p{font-size:8.5px;line-height:1.25;}
.page-content .chat-log{max-height:none;min-height:0;margin-bottom:5px;gap:4px;}
.page-content .chat-bubble{font-size:9px;padding:5px 7px;line-height:1.25;}
.page-content .quick{gap:4px;}
.page-content .quick button{padding:5px 7px;font-size:8.5px;}
.page-content .ask-row{margin-top:5px;padding:3px 3px 3px 7px;}
.page-content .ask-row input{font-size:9px;}
.page-content .ask-row button{width:23px;height:23px;font-size:10px;}
.page-content .perf-score{font-size:18px;margin:0;}
.page-content .perf-sub{font-size:8px;margin-bottom:0;}
.page-content .trend-svg{height:67px;}
.page-content .trend-labels{font-size:7px;}
.page-content .breakdown{margin-top:5px;padding-top:5px;font-size:8.5px;}
.page-content .breakdown-top{margin-bottom:0;}
.page-content .radar-wrap{width:116px;height:73px;margin:0 auto;}
.page-content .diamond{width:43px;height:43px;}
.page-content .radar-lbl{font-size:7.5px;}
.page-content .radar-lbl small{font-size:7px;}
.page-content .tests-panel{height:100%;margin:0;padding:8px 10px;}
.page-content .tests-panel .card-head{margin-bottom:5px;}
.page-content .carousel-track{gap:7px;padding:0;}
.page-content .test-card{flex:0 0 128px;height:67px;padding:8px;border-radius:9px;}
.page-content .test-card strong{font-size:11px;margin-bottom:2px;}
.page-content .test-card p{font-size:8px;margin-bottom:3px;}
.page-content .test-card a{font-size:8.5px;}
.page-content .test-card .badge-new{top:5px;right:5px;font-size:7px;padding:2px 5px;}
.page-content .carousel-arrow{top:18px;width:24px;height:24px;}
.page-content .plan-card{padding:10px!important;}
.page-content .plan-ring-wrap{gap:8px;}
.page-content .plan-ring{width:45px;height:45px;}
.page-content .plan-ring .hole{width:36px;height:36px;font-size:10px;}
.page-content .plan-sub{font-size:9px;}
.page-content .tasks{gap:5px;margin:7px 0 2px;}
.page-content .task-row{gap:7px;font-size:9.5px;}
.page-content .task-row .dot{width:15px;height:15px;font-size:9px;}
.page-content .task-row .lbl b{line-height:1.15;}
.page-content .task-row .lbl small{font-size:8px;}
.page-content .continue-btn{height:28px;margin-top:6px;font-size:10px;}
.page-content .class-row{gap:7px;margin-top:7px;}
.page-content .class-row img{width:38px;height:38px;}
.page-content .class-row .txt b{font-size:9px;}
.page-content .class-row .txt span{font-size:8px;margin-top:1px;}
.page-content .live-tag{font-size:7px;}
.page-content .join-btn{padding:5px 7px;font-size:8.5px;}
.page-content .announcement{height:62px;padding:7px 7px 7px 58px;font-size:8px;}
.page-content .announcement:before{font-size:31px;left:10px;top:13px;}
.page-content .announcement b{font-size:9px;}
.page-content .announcement p{margin:2px 0;font-size:8px;}
.page-content .announcement a{font-size:8px;}
.page-content .motivation{height:48px;padding:7px 10px 6px 34px;font-size:8.5px;}
.page-content .motivation:before{font-size:22px;left:10px;top:2px;}
.page-content .motivation b{font-size:9.5px;margin-top:2px;}
@media(max-height:700px){
.page-content .dashboard-left{grid-template-rows:91px 99px minmax(0,1fr) 92px}
.page-content .dashboard-right{grid-template-rows:minmax(230px,1.5fr) minmax(112px,.8fr) minmax(76px,.5fr) 43px}
.page-content .layout{min-height:0}
}
@media(max-width:1100px){
.page-content .layout{grid-template-columns:minmax(0,1fr) 278px}
}
@media(max-width:820px){
.page-content .layout{grid-template-columns:1fr}
.page-content .dashboard-right{display:none}
.page-content .dashboard-left{grid-template-rows:95px 105px minmax(0,1fr) 95px}
.page-content .lower{grid-template-columns:1fr 1fr}
.page-content .lower .card:last-child{display:none}
}
/* Dashboard is a full-height, non-scrolling grid. Rather than hard-coding
   100dvh (which zoom shrinks), let the content well stretch and hand the
   leftover height to the grid. */
.page-content{display:flex;flex-direction:column}
.page-content .layout{flex:1;min-height:0;height:auto}

</style>
@endpush

@section('content')
<div class="layout">
            <section class="dashboard-left">
                <div class="hero">
                    <div class="hero-block"><div class="eyebrow">CURRENT TEST</div><h2 id="heroTestName">PTE Academic</h2><div class="test-switch"><button type="button" onclick="toggleTestDropdown()">Switch Test ▾</button></div></div>
                    <div class="hero-metric"><div class="lbl">Overall Score</div><div class="hero-ring"><svg viewBox="0 0 88 88"><circle cx="44" cy="44" r="38" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="8"/><circle cx="44" cy="44" r="38" fill="none" stroke="#fff" stroke-width="8" stroke-linecap="round" stroke-dasharray="238.8" stroke-dashoffset="29.2"/></svg><div class="val"><b>79</b><span>/90</span></div></div></div>
                    <div class="hero-metric"><div class="lbl">Target Score</div><strong>90</strong></div><div class="hero-metric"><div class="lbl">Percentile</div><strong>85<sup>th</sup></strong></div><div class="hero-improve">You're improving!<small>Keep practicing consistently to reach your target score.</small></div>
                </div>

                <div class="skill-grid" id="skillGrid"></div>

                <div class="lower">
                    <div class="card"><div class="card-head"><h3>Continue Learning</h3><a href="#" onclick="showToast('Study plan opened');return false">View Study Plan →</a></div><div id="continueLearning"></div></div>
                    <div class="card"><div class="card-head"><h3>AI Tutor <span class="tutor-badge">Beta</span></h3></div><div class="tutor-greet" id="tutorGreet"><div class="bot">🤖</div><div><h4>Hi Arjun! 👋</h4><p>Ask me anything or get help with your weak areas.</p></div></div><div class="chat-log hidden" id="chatLog"></div><div class="quick" id="quickPrompts"></div><div class="ask-row"><input type="text" id="askInput" placeholder="Ask anything..."><button type="button" id="askButton">➤</button></div></div>
                    <div class="card"><div class="card-head"><h3>Performance Overview</h3><a href="#" onclick="showToast('Analytics summary: your score improved by 8 points');return false">View Analytics →</a></div><small style="color:var(--muted);font-size:9px">Overall Score</small><div class="perf-score">79<sup>/90</sup><span class="perf-badge">+8</span></div><div class="perf-sub">vs last week</div><svg class="trend-svg" id="trendSvg" viewBox="0 0 250 110" preserveAspectRatio="none"></svg><div class="trend-labels" id="trendLabels"></div><div class="breakdown"><div class="breakdown-top"><span>Skills Breakdown</span></div><div class="radar-wrap" id="radarWrap"><div class="diamond"></div></div></div></div>
                </div>

                <div class="card tests-panel"><div class="card-head"><h3>Explore Other Tests</h3><a href="#" onclick="showToast('All available tests are displayed');return false">View All Tests →</a></div><div class="carousel-row"><button type="button" class="carousel-arrow left" onclick="scrollTests(-1)">‹</button><div class="carousel-track" id="testsTrack"></div><button type="button" class="carousel-arrow right" onclick="scrollTests(1)">›</button></div></div>
            </section>

            <aside class="dashboard-right">
                <div class="card plan-card"><div class="card-head"><h3>Today's Plan</h3><a href="#" onclick="showToast('Your plan contains 6 tasks');return false">View Plan</a></div><div class="plan-ring-wrap"><div class="plan-ring" id="planRing"><div class="hole" id="planRingLabel"></div></div><div class="plan-sub">Tasks Completed</div></div><div class="tasks" id="taskList"></div><button type="button" class="continue-btn" onclick="continuePlan()">Continue Plan →</button></div>
                <div class="card"><div class="card-head"><h3>Upcoming Live Classes</h3><a href="{{ route('live-classes.index') }}">View All</a></div><div id="liveClassesList"></div></div>
                <div class="card"><div class="card-head"><h3>Announcements</h3><a href="#" onclick="showToast('No additional announcements');return false">View All</a></div><div class="announcement"><b>Scholar Leaderboard<br>May 2024</b><p>Check your rank and win exciting rewards!</p><a href="#" onclick="showToast('You are currently ranked #24');return false">View Now →</a></div></div>
                <div class="motivation"><small>Daily Motivation</small><b>Discipline today, success tomorrow.</b></div>
            </aside>
        </div>

<div class="modal-overlay" id="upgradeOverlay"><div class="modal"><button type="button" class="modal-close" onclick="closeModal('upgradeOverlay')">✕</button><h2>👑 Upgrade to Pro</h2><p class="sub">Unlock unlimited mocks, AI feedback, advanced analytics and more.</p><ul style="margin:0 0 18px;padding-left:18px;font-size:13px;line-height:1.9"><li>Unlimited mock tests</li><li>Full AI feedback on every skill</li><li>Advanced performance analytics</li><li>Priority support</li></ul><button type="button" class="btn primary" style="width:100%" onclick="showToast('This is a UI demo — no payment is processed.');closeModal('upgradeOverlay')">Continue</button></div></div>
@endsection

@push('scripts')
<script>
'use strict';
const STORAGE_KEY='schoolar_dashboard_blade_v1';
const DEFAULT_STATE={
    currentTest:'PTE Academic',streakCount:12,unreadNotifications:6,
    skillScores:{speaking:80,listening:76,reading:82,writing:78},
    tasks:[{id:'warmup',label:'Warm up: Daily Vocabulary',sub:null,done:true},{id:'reading_fib',label:'Practice: Reading – Fill in the Blanks',sub:null,done:true},{id:'ai_speaking',label:'AI Feedback: Speaking',sub:null,done:true},{id:'live_speaking',label:'Live Class: PTE Speaking',sub:'7:30 PM – 8:30 PM',done:false},{id:'mock_full',label:'Mock Test: Full Length',sub:null,done:false},{id:'review',label:'Review Answers',sub:null,done:false}],
    learning:{featured:{tag:'Reading & Writing',title:'Fill in the Blanks',pct:45},items:[{icon:'🎧',title:'Summarize Written Text',sub:'Listening',pct:70},{icon:'🗣️',title:'Repeat Sentence',sub:'Speaking',pct:60},{icon:'✏️',title:'Essay: Discuss Both Views',sub:'Writing',pct:30}]},
    chat:[],liveClasses:[{id:1,title:'PTE Speaking Masterclass',sub:'Master Fluency & Pronunciation',time:'Today, 7:30 PM',img:'https://i.pravatar.cc/120?img=47',joined:false},{id:2,title:'Writing Workshop',sub:'Essay Writing Techniques',time:'Tomorrow, 6:00 PM',img:'https://i.pravatar.cc/120?img=13',joined:false}],
    trend:{labels:['May 1','May 8','May 15','May 22','May 29'],values:[58,63,60,71,79]}
};
const skills={speaking:{label:'Speaking',icon:'🎤',bg:'#EEF2FF',fg:'#5A4CF0',status:'Strong',delta:6},listening:{label:'Listening',icon:'🎧',bg:'#EBF4FF',fg:'#1677F1',status:'Good',delta:4},reading:{label:'Reading',icon:'📗',bg:'#E5F9F5',fg:'#08A899',status:'Very Good',delta:8},writing:{label:'Writing',icon:'✏️',bg:'#FFF1E9',fg:'#FF6822',status:'Good',delta:5}};
const otherTests=[{name:'IELTS',sub:'IELTS Academic',color:'#DA1E28'},{name:'TOEFL iBT',sub:'TOEFL iBT',color:'#7A1FA2'},{name:'🌞 Duolingo',sub:'DET',color:'#F7931E',badge:'New'},{name:'GRE',sub:'GRE General Test',color:'#111827'},{name:'SAT',sub:'SAT (Digital)',color:'#0B57D0'},{name:'🔵 OET',sub:'OET Medicine',color:'#0EA5A0'}];
const notifications=[{title:'New Sectional Test unlocked',time:'2h ago'},{title:'Your Repeat Sentence accuracy dropped 8%',time:'5h ago'},{title:'Mira suggests a Focus Practice session',time:'1d ago'},{title:'Streak milestone: 12 days in a row 🔥',time:'1d ago'},{title:'Weekly performance report is ready',time:'2d ago'},{title:'Live Class starting soon',time:'3d ago'}];
const quickPrompts=['How can I improve my fluency?','Explain this answer to me','Give me ideas for my essay'];
let state=loadState(),toastTimer;

function defaults(){return JSON.parse(JSON.stringify(DEFAULT_STATE));}
function loadState(){try{const saved=JSON.parse(localStorage.getItem(STORAGE_KEY)||'null');return saved?Object.assign(defaults(),saved):defaults();}catch{return defaults();}}
function saveState(){try{localStorage.setItem(STORAGE_KEY,JSON.stringify(state));}catch(error){console.warn('Dashboard progress could not be saved',error);}}
function esc(value){return String(value).replace(/[&<>"']/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));}
function showToast(message){const toast=document.getElementById('toast');toast.textContent=message;toast.classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>toast.classList.remove('show'),2400);}
function closeModal(id){document.getElementById(id).classList.remove('open');}
function openUpgrade(){document.getElementById('upgradeOverlay').classList.add('open');}

function renderTop(){document.getElementById('testPillLabel').textContent=state.currentTest;document.getElementById('heroTestName').textContent=state.currentTest;document.getElementById('streakChipCount').textContent=state.streakCount;const badge=document.getElementById('bellBadge');badge.textContent=state.unreadNotifications;badge.style.display=state.unreadNotifications?'':'none';document.getElementById('notificationItems').innerHTML=notifications.map(item=>'<div class="ditem"><b>'+esc(item.title)+'</b><span>'+esc(item.time)+'</span></div>').join('');}
function renderSkills(){document.getElementById('skillGrid').innerHTML=Object.entries(skills).map(([key,meta])=>{const score=state.skillScores[key],pct=Math.round(score/90*100);return '<button type="button" class="skill-card" onclick="showToast(\''+meta.label+' practice selected\')" style="text-align:left"><div class="skill-top"><span class="ic" style="background:'+meta.bg+';color:'+meta.fg+'">'+meta.icon+'</span>'+meta.label+'<span class="chev">›</span></div><div class="skill-body"><div class="ring" style="background:conic-gradient('+meta.fg+' '+pct+'%,#E9EAFA 0)"><div class="hole"><b>'+score+'</b><span>/90</span></div></div><div class="skill-status"><b>'+meta.status+'</b><small>↗ +'+meta.delta+' from last test</small></div></div></button>';}).join('');}
function renderLearning(){const learning=state.learning;document.getElementById('continueLearning').innerHTML='<button type="button" class="featured-item" onclick="advanceLearning(\'featured\')" style="width:100%;border:0;text-align:left"><span class="ic">🎧</span><span style="flex:1"><span class="tag">'+esc(learning.featured.tag)+'</span><h4>'+esc(learning.featured.title)+'</h4><span class="mini-bar"><i style="width:'+learning.featured.pct+'%"></i></span><small>'+learning.featured.pct+'% Completed</small></span><span class="arrow">›</span></button>'+learning.items.map((item,index)=>'<button type="button" class="learn-item" onclick="advanceLearning('+index+')" style="width:100%;border:0;background:transparent;text-align:left"><span class="bubble">'+item.icon+'</span><span class="txt"><b>'+esc(item.title)+'</b><span>'+esc(item.sub)+'</span></span><span class="pctwrap">'+item.pct+'%<span class="mini-bar"><i style="width:'+item.pct+'%"></i></span></span><span class="chev">›</span></button>').join('');}
function renderChat(){const log=document.getElementById('chatLog'),greet=document.getElementById('tutorGreet');greet.classList.toggle('hidden',state.chat.length>0);log.classList.toggle('hidden',state.chat.length===0);log.innerHTML=state.chat.map(message=>'<div class="chat-bubble '+message.role+'">'+esc(message.text)+'</div>').join('');log.scrollTop=log.scrollHeight;document.getElementById('quickPrompts').innerHTML=quickPrompts.map(prompt=>'<button type="button" data-prompt="'+esc(prompt)+'">'+esc(prompt)+'</button>').join('');document.querySelectorAll('[data-prompt]').forEach(button=>button.addEventListener('click',()=>sendChat(button.dataset.prompt)));}
function renderTrend(){const values=state.trend.values,svg=document.getElementById('trendSvg'),w=250,h=110,pad=8;const points=values.map((value,index)=>[pad+index*(w-pad*2)/(values.length-1),h-pad-(value/100)*(h-pad*2)]);const path=points.map((point,index)=>(index?'L':'M')+point[0].toFixed(1)+','+point[1].toFixed(1)).join(' '),area=path+' L'+points.at(-1)[0]+','+h+' L'+points[0][0]+','+h+' Z';svg.innerHTML='<defs><linearGradient id="trendFill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#5A4CF0" stop-opacity=".22"/><stop offset="100%" stop-color="#5A4CF0" stop-opacity="0"/></linearGradient></defs><path d="'+area+'" fill="url(#trendFill)"/><path d="'+path+'" fill="none" stroke="#5A4CF0" stroke-width="2.5"/>'+points.map(point=>'<circle cx="'+point[0]+'" cy="'+point[1]+'" r="3" fill="#5A4CF0"/>').join('');document.getElementById('trendLabels').innerHTML=state.trend.labels.map(label=>'<span>'+label+'</span>').join('');document.getElementById('radarWrap').innerHTML='<div class="diamond"></div><div class="radar-lbl" style="top:0;left:50%;transform:translateX(-50%)">Speaking<small>'+state.skillScores.speaking+'</small></div><div class="radar-lbl" style="bottom:0;left:50%;transform:translateX(-50%)">Reading<small>'+state.skillScores.reading+'</small></div><div class="radar-lbl" style="left:0;top:50%;transform:translateY(-50%)">Writing<small>'+state.skillScores.writing+'</small></div><div class="radar-lbl" style="right:0;top:50%;transform:translateY(-50%)">Listening<small>'+state.skillScores.listening+'</small></div>';}
function renderTests(){document.getElementById('testsTrack').innerHTML=otherTests.map(test=>'<article class="test-card">'+(test.badge?'<span class="badge-new">'+test.badge+'</span>':'')+'<strong style="color:'+test.color+'">'+esc(test.name)+'</strong><p>'+esc(test.sub)+'</p><a href="#" onclick="startTest(\''+esc(test.sub)+'\');return false">Start Practicing</a></article>').join('');}
function renderTasks(){const done=state.tasks.filter(task=>task.done).length,total=state.tasks.length,pct=Math.round(done/total*100);document.getElementById('planRing').style.background='conic-gradient(var(--indigo) '+pct+'%,#E0E2F6 0)';document.getElementById('planRingLabel').textContent=done+'/'+total;document.getElementById('taskList').innerHTML=state.tasks.map(task=>'<button type="button" class="task-row '+(task.done?'done':'')+'" onclick="toggleTask(\''+task.id+'\')" style="border:0;background:transparent;text-align:left;padding:0"><span class="dot">'+(task.done?'✓':'')+'</span><span class="lbl"><b>'+esc(task.label)+'</b>'+(task.sub?'<small>'+esc(task.sub)+'</small>':'')+'</span></button>').join('');}
function renderClasses(){document.getElementById('liveClassesList').innerHTML=state.liveClasses.map(item=>'<div class="class-row"><img src="'+item.img+'" alt=""><div class="txt"><div class="liverow"><span class="live-tag">LIVE</span><b>'+esc(item.title)+'</b></div><span>'+esc(item.sub)+'</span><span>🕒 '+esc(item.time)+'</span></div><button type="button" class="join-btn '+(item.joined?'joined':'')+'" onclick="joinClass('+item.id+')">'+(item.joined?'Joined ✓':'Join')+'</button></div>').join('');}
function renderAll(){renderTop();renderSkills();renderLearning();renderChat();renderTrend();renderTests();renderTasks();renderClasses();}

function toggleTestDropdown(){document.getElementById('testPillDropdown').classList.toggle('open');}
function switchTest(name){state.currentTest=name;saveState();renderTop();document.getElementById('testPillDropdown').classList.remove('open');showToast('Switched to '+name);}
function markAllRead(event){event.preventDefault();state.unreadNotifications=0;saveState();renderTop();showToast('All notifications marked as read');}
function resetDashboard(event){event.preventDefault();if(confirm('Reset all dashboard data back to defaults?')){localStorage.removeItem(STORAGE_KEY);location.reload();}}
function toggleTask(id){const task=state.tasks.find(item=>item.id===id);if(!task)return;task.done=!task.done;saveState();renderTasks();}
function continuePlan(){const task=state.tasks.find(item=>!item.done);if(!task){showToast('All tasks completed — excellent work!');return;}task.done=true;saveState();renderTasks();showToast('Completed: '+task.label);}
function aiReply(message){const lower=message.toLowerCase();if(lower.includes('fluency'))return 'To improve fluency, shadow native speakers for 10 minutes daily, record Repeat Sentence tasks, and reduce long pauses. Focus on consistency under time pressure.';if(lower.includes('essay')||lower.includes('writing'))return 'Use a clear thesis, one paragraph per viewpoint with examples, your opinion, and a concise conclusion. Aim for 200–300 words.';if(lower.includes('explain'))return 'Tell me the exact question or answer and I will break down why it is correct or incorrect.';if(lower.includes('score')||lower.includes('target'))return 'You are at 79/90 with an 11-point gap. Focused Reading and Writing practice should move the overall score fastest.';return 'Based on your recent activity, Repeat Sentence and speaking accuracy deserve focused practice this week. Start with one timed 15-minute session.';}
function sendChat(message){if(!message.trim())return;state.chat.push({role:'user',text:message},{role:'bot',text:aiReply(message)});state.chat=state.chat.slice(-20);saveState();renderChat();}
function sendChatFromInput(){const input=document.getElementById('askInput'),value=input.value.trim();if(!value)return;input.value='';sendChat(value);}
function advanceLearning(index){const item=index==='featured'?state.learning.featured:state.learning.items[index];item.pct=Math.min(100,item.pct+5);saveState();renderLearning();showToast(item.title+' progress: '+item.pct+'%');}
function joinClass(id){const item=state.liveClasses.find(entry=>entry.id===id);if(!item||item.joined)return;item.joined=true;saveState();renderClasses();showToast('You joined the class — check your email for the link.');}
function startTest(name){state.currentTest=name;saveState();renderTop();showToast(name+' selected for practice');}
function scrollTests(direction){document.getElementById('testsTrack').scrollBy({left:direction*220,behavior:'smooth'});}

document.getElementById('bellBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('bellDropdown').classList.toggle('open');});
document.getElementById('avatarBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('profileDropdown').classList.toggle('open');});
document.getElementById('profileBtn').addEventListener('click',event=>{event.stopPropagation();document.getElementById('profileDropdown').classList.toggle('open');});
document.getElementById('testPillBtn').addEventListener('click',event=>{event.stopPropagation();toggleTestDropdown();});
document.getElementById('askButton').addEventListener('click',sendChatFromInput);document.getElementById('askInput').addEventListener('keydown',event=>{if(event.key==='Enter')sendChatFromInput();});
document.addEventListener('click',event=>{document.querySelectorAll('.dropdown.open,.test-dropdown.open').forEach(dropdown=>{if(!dropdown.parentElement.contains(event.target)&&!event.target.closest('.test-switch'))dropdown.classList.remove('open');});if(window.innerWidth<=1100&&!document.getElementById('sidebar').contains(event.target))document.getElementById('sidebar').classList.remove('open');});
document.querySelectorAll('.modal-overlay').forEach(overlay=>overlay.addEventListener('click',event=>{if(event.target===overlay)overlay.classList.remove('open');}));

renderAll();
</script>
@endpush