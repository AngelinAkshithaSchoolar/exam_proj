@extends('layouts.app')

@section('title', 'Practice — schoolar.ai')
@section('page-title', 'Practice')
@section('page-sub', "Practice more, improve faster. Track your progress and master every skill.")

@php($active = 'practice')

@push('styles')
<style>
:root{
    --bg:#F4F6FB; --card:#FFFFFF; --text:#12142B; --muted:#767B94;
    --border:#EBEDF5; --indigo:#5A4CF0; --indigo-dark:#4636D6;
    --green:#12B76A; --red:#F0446E; --orange:#F7931E; --navy:#0E1030;
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
.page-content .overview{display:flex; align-items:center; gap:22px; flex-wrap:wrap;}
.page-content .donut-wrap{position:relative; width:150px; height:150px; flex-shrink:0;}
.page-content .donut-wrap svg{transform:rotate(-90deg);}
.page-content .donut-center{position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;}
.page-content .donut-center b{font-size:24px; font-weight:900;}
.page-content .donut-center span{font-size:11px; color:var(--muted);}
.page-content .mini-stats{display:flex; gap:12px; flex:1; flex-wrap:wrap;}
.page-content .mini-stat{background:#F8F9FD; border:1px solid var(--border); border-radius:14px; padding:12px 14px; min-width:112px; flex:1;}
.page-content .mini-stat .lbl{display:flex; align-items:center; gap:6px; font-size:12px; color:var(--muted); font-weight:700; margin-bottom:8px;}
.page-content .mini-stat b{font-size:19px; font-weight:900; display:block;}
.page-content .mini-stat .delta{font-size:11px; font-weight:800;}
.page-content .delta.up{color:var(--green);}
.page-content .delta.down{color:var(--red);}
.page-content .weekly-box{min-width:150px;}
.page-content .weekly-box .lbl{font-size:12px; color:var(--muted); font-weight:700;}
.page-content .weekly-box b{font-size:22px; display:block; margin:2px 0 8px;}
.page-content .bars{display:flex; align-items:flex-end; gap:5px; height:42px;}
.page-content .bars i{flex:1; background:var(--indigo); opacity:.85; border-radius:3px 3px 0 0; display:block;}
.page-content .week-lbls{display:flex; gap:5px; margin-top:4px;}
.page-content .week-lbls span{flex:1; text-align:center; font-size:9.5px; color:var(--muted);}
.page-content .tabs-row{display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin:22px 0 16px;}
.page-content .tabs{display:flex; gap:6px; background:#fff; border:1px solid var(--border); padding:5px; border-radius:14px; box-shadow:var(--shadow); flex-wrap:wrap;}
.page-content .tabs button{border:none; background:transparent; padding:9px 15px; border-radius:10px; font-weight:700; font-size:13px; color:var(--muted); display:flex; align-items:center; gap:6px;}
.page-content .tabs button.active{background:var(--indigo); color:#fff;}
.page-content .search-filters{display:flex; gap:10px; align-items:center;}
.page-content .search-box{display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--border); padding:9px 14px; border-radius:12px; box-shadow:var(--shadow); min-width:230px;}
.page-content .search-box input{border:none; outline:none; font-size:13px; width:100%; background:transparent;}
.page-content .filters-btn{display:flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--border); padding:9px 14px; border-radius:12px; font-weight:700; font-size:13px; box-shadow:var(--shadow); position:relative;}
.page-content .filters-panel{position:absolute; top:46px; right:0; width:220px; background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 12px 28px rgba(20,20,50,.14); padding:14px; z-index:50; display:none;}
.page-content .filters-panel.open{display:block;}
.page-content .filters-panel label{font-size:11.5px; font-weight:700; color:var(--muted); display:block; margin-bottom:6px;}
.page-content .filters-panel select{width:100%; padding:8px; border-radius:8px; border:1px solid var(--border); font-size:12.5px; margin-bottom:12px;}
.page-content .filters-panel button{width:100%; background:var(--indigo); color:#fff; border:none; padding:9px; border-radius:9px; font-weight:700; font-size:12.5px;}
.page-content .section-title{font-size:15px; font-weight:800; margin:0 0 14px;}
.page-content .type-grid{display:grid; grid-template-columns:repeat(5, 1fr); gap:14px;}
@media (max-width:1400px){
.page-content .type-grid{grid-template-columns:repeat(3,1fr);}
}
@media (max-width:900px){
.page-content .type-grid{grid-template-columns:repeat(2,1fr);}
}
.page-content .type-card{background:#fff; border:1px solid var(--border); border-radius:16px; padding:16px; box-shadow:var(--shadow); display:flex; flex-direction:column; gap:10px;}
.page-content .type-card .ic{width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:17px;}
.page-content .type-card h4{margin:0; font-size:13.5px; font-weight:800; line-height:1.25;}
.page-content .type-card .meta{font-size:12px; color:var(--muted);}
.page-content .type-card .meta b{color:var(--text); font-weight:800;}
.page-content .type-card .acc{font-size:12px;}
.page-content .type-card .acc b{font-weight:800;}
.page-content .type-card .prog{height:5px; background:#EEF0F7; border-radius:4px; overflow:hidden;}
.page-content .type-card .prog i{display:block; height:100%; border-radius:4px;}
.page-content .type-card .go{margin-top:2px; border:1px solid var(--indigo); color:var(--indigo); background:#fff; border-radius:10px; padding:8px; font-weight:800; font-size:12.5px; display:flex; align-items:center; justify-content:center; gap:6px;}
.page-content .type-card .go:hover{background:var(--indigo); color:#fff;}
.page-content .bookmark-star{margin-left:auto; background:none; border:none; font-size:15px; color:#D8DAEA;}
.page-content .bookmark-star.on{color:#F7931E;}
.page-content .view-more{display:block; text-align:center; margin-top:18px; font-weight:700; color:var(--indigo); font-size:13px;}
.page-content .mock-grid{display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:12px;}
@media (max-width:1100px){
.page-content .mock-grid{grid-template-columns:1fr;}
}
.page-content .mock-card{background:#fff; border:1px solid var(--border); border-radius:16px; padding:18px; box-shadow:var(--shadow); display:flex; flex-direction:column; gap:12px;}
.page-content .mock-card .ic{width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:17px; margin-bottom:2px;}
.page-content .mock-card h4{margin:0; font-size:14.5px; font-weight:800;}
.page-content .mock-card p{margin:2px 0 0; font-size:12px; color:var(--muted); line-height:1.5;}
.page-content .mock-card .meta-line{display:flex; gap:14px; font-size:11.5px; color:var(--muted); flex-wrap:wrap;}
.page-content .mock-card select{width:100%; padding:9px 10px; border-radius:10px; border:1px solid var(--border); font-size:12.5px; font-weight:600;}
.page-content .mock-card .chk-row{display:flex; flex-wrap:wrap; gap:6px;}
.page-content .mock-card .chk{border:1px solid var(--border); border-radius:8px; padding:5px 9px; font-size:11.5px; font-weight:700; cursor:pointer; user-select:none;}
.page-content .mock-card .chk.on{background:var(--indigo); color:#fff; border-color:var(--indigo);}
.page-content .mock-card .cta{border:none; padding:11px; border-radius:11px; font-weight:800; font-size:13px; color:#fff;}
.page-content .mock-card .stat-row{display:flex; justify-content:space-between; border-top:1px solid var(--border); padding-top:10px; margin-top:2px;}
.page-content .mock-card .stat-row div span{display:block; font-size:10.5px; color:var(--muted);}
.page-content .mock-card .stat-row div b{font-size:13.5px;}
.page-content .focus-card{background:linear-gradient(160deg,#5A4CF0,#4636D6); color:#fff; border-radius:var(--radius); padding:20px;}
.page-content .focus-card .tag{display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;}
.page-content .focus-card .tag b{font-size:14px; display:flex; gap:6px; align-items:center;}
.page-content .focus-card .tag span{background:rgba(255,255,255,.18); font-size:10.5px; font-weight:800; padding:3px 9px; border-radius:8px;}
.page-content .focus-card h4{margin:0 0 6px; font-size:15.5px;}
.page-content .focus-card p{margin:0 0 16px; font-size:12.5px; color:#DEDBFF;}
.page-content .focus-card button{width:100%; background:#fff; color:var(--indigo-dark); border:none; padding:11px; border-radius:11px; font-weight:800; font-size:13px; margin-bottom:10px;}
.page-content .focus-card a{display:block; text-align:center; font-size:12.5px; font-weight:700; color:#fff;}
.page-content .trend-svg{width:100%; height:130px;}
.page-content .recent-list{display:flex; flex-direction:column; gap:10px;}
.page-content .recent-item{display:flex; align-items:center; gap:10px; border:1px solid var(--border); border-radius:12px; padding:10px;}
.page-content .recent-item .ico{width:34px; height:34px; border-radius:9px; background:#F1F2FA; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0;}
.page-content .recent-item .txt{flex:1; min-width:0;}
.page-content .recent-item .txt b{display:block; font-size:12.5px; font-weight:700;}
.page-content .recent-item .txt span{font-size:11px; color:var(--muted);}
.page-content .badge-acc{font-size:11px; font-weight:800; padding:4px 9px; border-radius:8px;}
.page-content .badge-acc.good{background:#E9FBF3; color:var(--green);}
.page-content .badge-acc.mid{background:#FFF3E8; color:var(--orange);}
.page-content .badge-acc.bad{background:#FFECEF; color:var(--red);}
.page-content .streak-cal{display:flex; gap:6px; margin-top:10px;}
.page-content .streak-day{flex:1; text-align:center;}
.page-content .streak-day .d{font-size:11px; color:var(--muted); margin-bottom:6px; font-weight:700;}
.page-content .streak-day .c{width:26px; height:26px; margin:0 auto; border-radius:8px; background:#EEF0F7; display:flex; align-items:center; justify-content:center; font-size:13px; border:none; color:transparent;}
.page-content .streak-day .c.on{background:var(--green); color:#fff;}
.page-content .streak-count{display:flex; align-items:center; gap:8px; font-size:14px; font-weight:800; margin-top:2px;}
.page-content .modal-overlay{position:fixed; inset:0; background:rgba(14,16,48,.55); display:none; align-items:center; justify-content:center; z-index:200; padding:20px;}
.page-content .modal-overlay.open{display:flex;}
.page-content .modal{background:#fff; border-radius:20px; width:100%; max-width:620px; max-height:88vh; overflow:auto; padding:24px; position:relative; box-shadow:0 30px 60px rgba(0,0,0,.3);}
.page-content .modal-close{position:absolute; top:16px; right:16px; background:#F1F2FA; border:none; width:32px; height:32px; border-radius:9px; font-size:15px;}
.page-content .modal h2{margin:0 0 4px; font-size:18px; font-weight:800;}
.page-content .modal .sub{color:var(--muted); font-size:12.5px; margin-bottom:16px;}
.page-content .q-progress{display:flex; align-items:center; gap:10px; margin-bottom:16px;}
.page-content .q-progress .bar{flex:1; height:6px; background:#EEF0F7; border-radius:6px; overflow:hidden;}
.page-content .q-progress .bar i{display:block; height:100%; background:var(--indigo);}
.page-content .q-progress span{font-size:11.5px; font-weight:800; color:var(--muted); white-space:nowrap;}
.page-content .timer-chip{display:inline-flex; align-items:center; gap:6px; background:#FFF3E8; color:#B4700B; font-weight:800; font-size:12px; padding:5px 11px; border-radius:9px; margin-bottom:14px;}
.page-content .q-text{font-size:15.5px; font-weight:700; margin-bottom:16px; line-height:1.5;}
.page-content .opt{display:block; width:100%; text-align:left; border:1.5px solid var(--border); background:#fff; padding:12px 14px; border-radius:12px; margin-bottom:10px; font-size:13.5px; font-weight:600;}
.page-content .opt:hover{border-color:var(--indigo);}
.page-content .opt.selected{border-color:var(--indigo); background:#F1EFFF;}
.page-content .opt.correct{border-color:var(--green); background:#E9FBF3;}
.page-content .opt.wrong{border-color:var(--red); background:#FFECEF;}
.page-content .q-actions{display:flex; justify-content:space-between; align-items:center; margin-top:8px;}
.page-content .q-actions .left{display:flex; gap:8px;}
.page-content .btn{border:none; padding:11px 18px; border-radius:11px; font-weight:800; font-size:13px;}
.page-content .btn.primary{background:var(--indigo); color:#fff;}
.page-content .btn.primary:disabled{opacity:.45; cursor:not-allowed;}
.page-content .btn.ghost{background:#F1F2FA; color:var(--text);}
.page-content .star-btn{background:#F1F2FA; border:none; width:38px; height:38px; border-radius:11px; font-size:15px; color:#B7BADB;}
.page-content .star-btn.on{color:#F7931E;}
.page-content .result-box{text-align:center; padding:16px 0;}
.page-content .result-box .big{font-size:44px; font-weight:900; color:var(--indigo); margin:6px 0;}
.page-content .result-box p{color:var(--muted); font-size:13px; margin:0 0 20px;}
.page-content .result-row{display:flex; gap:10px; justify-content:center;}
.page-content .attempts-table{width:100%; border-collapse:collapse; font-size:13px;}
.page-content .attempts-table th{text-align:left; color:var(--muted); font-size:11.5px; text-transform:uppercase; letter-spacing:.03em; padding:8px 10px; border-bottom:1px solid var(--border);}
.page-content .attempts-table td{padding:10px; border-bottom:1px solid var(--border);}
.page-content .empty-state{text-align:center; padding:40px 10px; color:var(--muted);}
.page-content .empty-state .big-ic{font-size:34px; margin-bottom:10px;}
.page-content .hidden{display:none !important;}
/* ── Progress row: keep the donut + 4 skills + weekly box on ONE line ──
   The original used flex-wrap with min-width:112px tiles, which needs
   ~830px. A grid with minmax(0,1fr) tiles fits whatever width is left. */
.page-content .overview{
    display:grid;
    grid-template-columns:auto repeat(4, minmax(0,1fr)) auto;
    gap:11px;
    align-items:stretch;
    flex-wrap:nowrap;
}
.page-content .donut-wrap{width:126px;height:126px;flex-shrink:0}
.page-content .donut-wrap svg{width:100%;height:100%}
.page-content .donut-center b{font-size:20px}
.page-content .donut-center span{font-size:10px}
.page-content .mini-stat{
    min-width:0;padding:10px 11px;
    display:flex;flex-direction:column;justify-content:center;
}
.page-content .mini-stat .lbl{
    font-size:11px;gap:5px;margin-bottom:4px;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.page-content .mini-stat b{font-size:17px}
.page-content .mini-stat .delta{font-size:10px}
.page-content .weekly-box{min-width:124px}
.page-content .weekly-box b{font-size:18px;margin:2px 0 6px}

/* narrower screens: let it fold in stages instead of breaking */
@media(max-width:1150px){
    .page-content .overview{grid-template-columns:auto repeat(3, minmax(0,1fr))}
}
@media(max-width:900px){
    .page-content .overview{grid-template-columns:repeat(2, minmax(0,1fr))}
    .page-content .donut-wrap{grid-column:1/-1;justify-self:center}
}

</style>
@endpush

@section('content')
<div class="layout">
            <!-- ===== LEFT / CENTER COLUMN ===== -->
            <div>
                <!-- Progress overview -->
                <div class="card">
                    <div class="card-head"><h3>Your Progress Overview</h3></div>
                    <div class="overview">
                        <div class="donut-wrap">
                            <svg viewBox="0 0 150 150" width="150" height="150">
                                <circle cx="75" cy="75" r="62" fill="none" stroke="#EEF0F7" stroke-width="14"/>
                                <circle id="donutRing" cx="75" cy="75" r="62" fill="none" stroke="#5A4CF0" stroke-width="14"
                                        stroke-linecap="round"
                                        stroke-dasharray="<?= round(2*M_PI*62) ?>"
                                        stroke-dashoffset="<?= round(2*M_PI*62 * (1 - $overallAccuracy/100)) ?>"/>
                            </svg>
                            <div class="donut-center"><b id="overallAccVal"><?= $overallAccuracy ?>%</b><span>Overall Accuracy</span></div>
                        </div>

                        <?php foreach ($macroSkills as $key => $m):
                            $acc = \App\Support\PracticeData::macroAccuracy($key); $delta = $m['delta']; ?>
                        <div class="mini-stat">
                            <div class="lbl"><?= $m['icon'] ?> <?= $m['label'] ?></div>
                            <b id="macro_<?= $key ?>_val"><?= $acc ?>%</b>
                            <div class="delta <?= $delta >= 0 ? 'up' : 'down' ?>"><?= $delta >= 0 ? '↑' : '↓' ?> <?= abs($delta) ?>%</div>
                            <span style="font-size:10.5px;color:var(--muted);">Accuracy</span>
                        </div>
                        <?php endforeach; ?>

                        <div class="mini-stat weekly-box">
                            <span class="lbl">Questions Practiced</span>
                            <b id="weeklyTotalVal"><?= number_format($weeklyTotal) ?></b>
                            <span style="font-size:10.5px;color:var(--muted);">This Week</span>
                            <div class="bars" id="weeklyBars">
                                <?php foreach (session('weekly') as $v): $h = max(6, round(($v/$weeklyMax)*42)); ?>
                                <i style="height:<?= $h ?>px"></i>
                                <?php endforeach; ?>
                            </div>
                            <div class="week-lbls"><?php foreach ($weekDays as $d): ?><span><?= $d ?></span><?php endforeach; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Tabs + search -->
                <div class="tabs-row">
                    <div class="tabs" id="mainTabs">
                        <button class="active" data-tab="practice">👤 Practice</button>
                        <button data-tab="sectional">📗 Sectional Tests</button>
                        <button data-tab="full">🕐 Full Mock Tests</button>
                        <button data-tab="attempts">📄 My Attempts</button>
                        <button data-tab="bookmarks">🔖 Bookmarks</button>
                    </div>
                    <div class="search-filters">
                        <div class="search-box">🔍 <input id="typeSearch" placeholder="Search question type..." oninput="filterTypes()"></div>
                        <div class="rel">
                            <button class="filters-btn" id="filtersBtn">☰ Filters</button>
                            <div class="filters-panel" id="filtersPanel">
                                <label>Sort by accuracy</label>
                                <select id="sortSel" onchange="applySort()">
                                    <option value="default">Default</option>
                                    <option value="high">Highest first</option>
                                    <option value="low">Lowest first</option>
                                </select>
                                <label>Skill</label>
                                <select id="skillSel" onchange="filterTypes()">
                                    <option value="">All skills</option>
                                    <option value="speaking">Speaking</option>
                                    <option value="listening">Listening</option>
                                    <option value="reading">Reading</option>
                                </select>
                                <button onclick="document.getElementById('filtersPanel').classList.remove('open')">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: Practice -->
                <div class="tab-panel" id="tab-practice">
                    <h3 class="section-title">Practice by Question Types</h3>
                    <div class="type-grid" id="typeGrid">
                        <?php foreach ($questionTypes as $i => $t):
                            [$bg, $fg] = \App\Support\PracticeData::colorVars($t['color']);
                            $acc = \App\Support\PracticeData::typeAccuracy($t['id']); ?>
                        <div class="type-card" data-name="<?= strtolower($t['name']) ?>" data-macro="<?= $t['macro'] ?>" data-acc="<?= $acc ?>" style="<?= $i >= 10 ? '' : '' ?>">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="ic" style="background:<?= $bg ?>;color:<?= $fg ?>;"><?= $t['icon'] ?></div>
                                <button class="bookmark-star" onclick="quickBookmark(this,'<?= $t['id'] ?>')" title="Bookmark this skill">★</button>
                            </div>
                            <h4><?= htmlspecialchars($t['name']) ?></h4>
                            <div class="meta"><b><?= $t['count'] ?></b> Questions</div>
                            <div class="acc"><b id="typeacc_<?= $t['id'] ?>" style="color:<?= $fg ?>"><?= $acc ?>%</b> Accuracy</div>
                            <div class="prog"><i id="typebar_<?= $t['id'] ?>" style="width:<?= $acc ?>%;background:<?= $fg ?>"></i></div>
                            <button class="go" onclick="startQuiz('<?= $t['id'] ?>')">Practice Now →</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="#" class="view-more" onclick="return false;">View More Question Types ⌄</a>

                    <h3 class="section-title" style="margin-top:28px;">Mock Tests</h3>
                    <div class="mock-grid">
                        <div class="mock-card">
                            <div class="ic" style="background:#EFEBFF;color:#6C5CE0;">📋</div>
                            <h4>Practice Mock Test (Full)</h4>
                            <p>Take a full length test simulating the real exam environment.</p>
                            <div class="meta-line"><span>⏱ Full Test</span><span>🕒 3 hrs</span><span>📚 20 Sections</span></div>
                            <button class="cta" style="background:var(--indigo);" onclick="startFullMock()">Start Full Mock Test →</button>
                            <div class="stat-row">
                                <div><span>Last Score</span><b id="lastFullScore"><?= session('last_full_score')['score'] ?> / <?= session('last_full_score')['total'] ?></b></div>
                                <div><span>Attempted on</span><b id="lastFullDate"><?= htmlspecialchars(session('last_full_score')['date']) ?></b></div>
                            </div>
                        </div>

                        <div class="mock-card">
                            <div class="ic" style="background:#E9FBF3;color:#12B76A;">🟩</div>
                            <h4>Sectional Tests</h4>
                            <p>Practice specific sections to improve your weak areas.</p>
                            <label style="font-size:11.5px;color:var(--muted);font-weight:700;">Select Section</label>
                            <select id="sectionalSelect">
                                <option value="speaking">Speaking</option>
                                <option value="listening">Listening</option>
                                <option value="reading">Reading</option>
                            </select>
                            <button class="cta" style="background:var(--green);" onclick="startSectional()">Start Sectional Test →</button>
                            <div class="stat-row">
                                <div><span>Best Score</span><b id="lastSectionalScore"><?= session('last_sectional')['score'] ?> / <?= session('last_sectional')['total'] ?></b></div>
                                <div><span>Total Attempts</span><b id="lastSectionalAttempts"><?= session('last_sectional')['attempts'] ?></b></div>
                            </div>
                        </div>

                        <div class="mock-card">
                            <div class="ic" style="background:#FFF3E8;color:#F7931E;">🟧</div>
                            <h4>Custom Mock Test</h4>
                            <p>Create a custom test by selecting sections &amp; duration.</p>
                            <label style="font-size:11.5px;color:var(--muted);font-weight:700;">Select Sections</label>
                            <div class="chk-row" id="customChkRow">
                                <div class="chk on" data-key="speaking">Speaking</div>
                                <div class="chk on" data-key="listening">Listening</div>
                                <div class="chk" data-key="reading">Reading</div>
                            </div>
                            <button class="cta" style="background:var(--orange);" onclick="startCustom()">Create Custom Test →</button>
                            <div class="stat-row">
                                <div><span>Estimated Time</span><b id="customTime">60 min</b></div>
                                <div><span>Questions</span><b id="customQCount">30</b></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: Sectional -->
                <div class="tab-panel hidden" id="tab-sectional">
                    <h3 class="section-title">Sectional Tests</h3>
                    <div class="type-grid">
                        <?php foreach ($macroSkills as $key => $m): if ($key === 'writing') continue;
                            $acc = \App\Support\PracticeData::macroAccuracy($key); [$bg,$fg] = \App\Support\PracticeData::colorVars($key==='speaking'?'indigo':($key==='listening'?'teal':'violet')); ?>
                        <div class="type-card">
                            <div class="ic" style="background:<?= $bg ?>;color:<?= $fg ?>;"><?= $m['icon'] ?></div>
                            <h4><?= $m['label'] ?> Section</h4>
                            <div class="meta">All <?= $m['label'] ?> question types combined</div>
                            <div class="acc"><b style="color:<?= $fg ?>"><?= $acc ?>%</b> Accuracy</div>
                            <div class="prog"><i style="width:<?= $acc ?>%;background:<?= $fg ?>"></i></div>
                            <button class="go" onclick="startSectionalFor('<?= $key ?>')">Start Section →</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- TAB: Full mock -->
                <div class="tab-panel hidden" id="tab-full">
                    <h3 class="section-title">Full Mock Tests</h3>
                    <div class="mock-card" style="max-width:420px;">
                        <div class="ic" style="background:#EFEBFF;color:#6C5CE0;">📋</div>
                        <h4>PTE Academic — Full Mock</h4>
                        <p>A shortened demo covering every question type across Speaking, Listening and Reading.</p>
                        <div class="meta-line"><span>🕒 Demo timer</span><span>📚 10 questions</span></div>
                        <button class="cta" style="background:var(--indigo);" onclick="startFullMock()">Start Full Mock Test →</button>
                    </div>
                </div>

                <!-- TAB: My Attempts -->
                <div class="tab-panel hidden" id="tab-attempts">
                    <h3 class="section-title">My Attempts</h3>
                    <table class="attempts-table">
                        <thead><tr><th>Question type</th><th>Questions</th><th>Accuracy</th></tr></thead>
                        <tbody id="attemptsBody">
                        <?php foreach (session('recent') as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['name']) ?></td>
                                <td><?= (int)$r['count'] ?></td>
                                <td><?= (int)$r['accuracy'] ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- TAB: Bookmarks -->
                <div class="tab-panel hidden" id="tab-bookmarks">
                    <h3 class="section-title">Bookmarks</h3>
                    <div id="bookmarksList">
                        <?php if (empty(session('bookmarks'))): ?>
                        <div class="empty-state" id="bookmarksEmpty"><div class="big-ic">🔖</div>No bookmarks yet. Star a question type or a question while practicing to save it here.</div>
                        <?php else: foreach (session('bookmarks') as $b): ?>
                        <div class="recent-item" data-key="<?= htmlspecialchars($b['key']) ?>">
                            <div class="ico">🔖</div>
                            <div class="txt"><b><?= htmlspecialchars($b['type_name']) ?></b><span><?= htmlspecialchars($b['question']) ?></span></div>
                            <button class="btn ghost" onclick="removeBookmark(this,'<?= htmlspecialchars($b['type'],ENT_QUOTES) ?>','<?= htmlspecialchars(addslashes($b['question']),ENT_QUOTES) ?>')">Remove</button>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== RIGHT COLUMN ===== -->
            <div>
                <div class="focus-card">
                    <div class="tag"><b>🎯 Today's Focus</b><span>Suggested by Mira</span></div>
                    <h4>Improve your Repeat Sentence fluency</h4>
                    <p>Your accuracy dropped by 8% this week.</p>
                    <button onclick="startQuiz('repeat_sentence')">Start Focus Practice</button>
                    <a href="#">View Study Plan →</a>
                </div>

                <div class="card">
                    <div class="card-head"><h3>Performance Trend</h3><a href="#">View Details</a></div>
                    <svg class="trend-svg" id="trendSvg" viewBox="0 0 300 130" preserveAspectRatio="none"></svg>
                </div>

                <div class="card">
                    <div class="card-head"><h3>Recent Practice</h3><a href="#" onclick="switchTab('attempts');return false;">View All</a></div>
                    <div class="recent-list" id="recentList">
                        <?php foreach (session('recent') as $r):
                            $acc = (int)$r['accuracy'];
                            $cls = $acc >= 70 ? 'good' : ($acc >= 60 ? 'mid' : 'bad'); ?>
                        <div class="recent-item">
                            <div class="ico">📄</div>
                            <div class="txt"><b><?= htmlspecialchars($r['name']) ?></b><span><?= (int)$r['count'] ?> Questions</span></div>
                            <div class="badge-acc <?= $cls ?>"><?= $acc ?>%</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="#" class="view-more" onclick="switchTab('attempts');return false;">Go to Practice History →</a>
                </div>

                <div class="card">
                    <div class="card-head"><h3>🔥 Practice Streak</h3></div>
                    <div class="streak-count"><span id="streakCountVal"><?= (int)session('streak_count') ?></span> Days in a row</div>
                    <div class="streak-cal" id="streakCal">
                        <?php foreach ($weekDays as $i => $d): $on = session('streak_days')[$i]; ?>
                        <div class="streak-day">
                            <div class="d"><?= $d ?></div>
                            <button class="c <?= $on ? 'on' : '' ?>" onclick="toggleStreak(<?= $i ?>, this)"><?= $on ? '✓' : '' ?></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

<!-- ============ QUIZ MODAL ============ -->
<div class="modal-overlay" id="quizOverlay">
    <div class="modal" id="quizModal">
        <button class="modal-close" onclick="closeQuiz()">✕</button>
        <div id="quizBody"></div>
    </div>
</div>

<!-- ============ UPGRADE MODAL ============ -->
<div class="modal-overlay" id="upgradeOverlay">
    <div class="modal" style="max-width:420px;">
        <button class="modal-close" onclick="document.getElementById('upgradeOverlay').classList.remove('open')">✕</button>
        <h2>👑 Upgrade to Pro</h2>
        <p class="sub">Unlock unlimited practice, full mock tests, advanced analytics and priority support.</p>
        <ul style="margin:0 0 18px;padding-left:18px;font-size:13px;line-height:1.9;color:var(--text);">
            <li>Unlimited practice on every question type</li>
            <li>Full-length mock tests, no limits</li>
            <li>Advanced performance analytics</li>
            <li>Priority support from the schoolar.ai team</li>
        </ul>
        <button class="btn primary" style="width:100%;" onclick="showToast('This is a UI demo — no payment is processed.'); document.getElementById('upgradeOverlay').classList.remove('open');">Continue</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* =========================================================================
   DATA FROM PHP
   ========================================================================= */
const QUESTION_BANK = <?= json_encode($questionBank, JSON_UNESCAPED_UNICODE) ?>;
const TYPE_META = <?= json_encode(array_map(function($t){ return ['id'=>$t['id'],'name'=>$t['name'],'macro'=>$t['macro']]; }, $questionTypes), JSON_UNESCAPED_UNICODE) ?>;
let TREND = <?= json_encode($trend) ?>;

/* =========================================================================
   GENERIC HELPERS
   ========================================================================= */
function post(action, data = {}) {
    const body = new URLSearchParams({ action, ...data });
    return fetch('{{ route('practice.api') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body
    }).then(r => r.json());
}
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => t.classList.remove('show'), 2600);
}
document.addEventListener('click', (e) => {
    document.querySelectorAll('.dropdown.open').forEach(dd => {
        if (!dd.parentElement.contains(e.target)) dd.classList.remove('open');
    });
    const fp = document.getElementById('filtersPanel');
    if (fp.classList.contains('open') && !document.getElementById('filtersBtn').contains(e.target) && !fp.contains(e.target)) {
        fp.classList.remove('open');
    }
});

/* =========================================================================
   TABS
   ========================================================================= */
function switchTab(name) {
    document.querySelectorAll('#mainTabs button').forEach(b => b.classList.toggle('active', b.dataset.tab === name));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('tab-' + name).classList.remove('hidden');
}
document.getElementById('mainTabs').addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-tab]');
    if (btn) switchTab(btn.dataset.tab);
});

/* =========================================================================
   SEARCH / FILTER / SORT (Practice tab)
   ========================================================================= */
function filterTypes() {
    const q = document.getElementById('typeSearch').value.trim().toLowerCase();
    const skill = document.getElementById('skillSel').value;
    document.querySelectorAll('#typeGrid .type-card').forEach(card => {
        const matchesName = card.dataset.name.includes(q);
        const matchesSkill = !skill || card.dataset.macro === skill;
        card.style.display = (matchesName && matchesSkill) ? '' : 'none';
    });
}
function applySort() {
    const grid = document.getElementById('typeGrid');
    const val = document.getElementById('sortSel').value;
    const cards = Array.from(grid.children);
    if (val === 'default') {
        cards.sort((a, b) => a.dataset.origIndex - b.dataset.origIndex);
    } else {
        cards.sort((a, b) => val === 'high'
            ? b.dataset.acc - a.dataset.acc
            : a.dataset.acc - b.dataset.acc);
    }
    cards.forEach(c => grid.appendChild(c));
}
document.querySelectorAll('#typeGrid .type-card').forEach((c, i) => c.dataset.origIndex = i);
document.getElementById('filtersBtn').addEventListener('click', () => document.getElementById('filtersPanel').classList.toggle('open'));

/* =========================================================================
   TOP BAR: bell / avatar dropdowns
   ========================================================================= */
document.getElementById('bellBtn').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('bellDropdown').classList.toggle('open');
});
document.getElementById('avatarBtn').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('profileDropdown').classList.toggle('open');
});
document.getElementById('profileBtn').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('profileDropdown').classList.toggle('open');
});
function markAllRead(e) {
    e.preventDefault();
    post('mark_notifications_read').then(() => {
        document.getElementById('bellBadge').style.display = 'none';
        showToast('All notifications marked as read');
    });
}
function resetProgress(e) {
    e.preventDefault();
    if (confirm('Reset all practice progress back to defaults?')) {
        post('reset_progress').then(() => location.reload());
    }
}
function openUpgrade() { document.getElementById('upgradeOverlay').classList.add('open'); }

/* =========================================================================
   STREAK CALENDAR
   ========================================================================= */
function toggleStreak(idx, el) {
    post('toggle_streak_day', { idx }).then(res => {
        el.classList.toggle('on', res.days[idx]);
        el.textContent = res.days[idx] ? '✓' : '';
        document.getElementById('streakCountVal').textContent = res.count;
        document.getElementById('streakChipCount').textContent = res.count;
    });
}

/* =========================================================================
   BOOKMARKS
   ========================================================================= */
function quickBookmark(btn, typeId) {
    const meta = TYPE_META.find(t => t.id === typeId);
    post('toggle_bookmark', { type: typeId, question: meta.name + ' (skill overview)' }).then(res => {
        btn.classList.toggle('on', res.bookmarked);
        showToast(res.bookmarked ? 'Bookmarked' : 'Removed bookmark');
        renderBookmarks(res.bookmarks);
    });
}
function removeBookmark(btn, type, question) {
    post('toggle_bookmark', { type, question }).then(res => renderBookmarks(res.bookmarks));
}
function renderBookmarks(list) {
    const wrap = document.getElementById('bookmarksList');
    if (!list.length) {
        wrap.innerHTML = '<div class="empty-state"><div class="big-ic">🔖</div>No bookmarks yet. Star a question type or a question while practicing to save it here.</div>';
        return;
    }
    wrap.innerHTML = list.map(b => `
        <div class="recent-item">
            <div class="ico">🔖</div>
            <div class="txt"><b>${escapeHtml(b.type_name)}</b><span>${escapeHtml(b.question)}</span></div>
            <button class="btn ghost" onclick="removeBookmarkKey('${b.type}', this)" data-q="${encodeURIComponent(b.question)}">Remove</button>
        </div>`).join('');
}
function removeBookmarkKey(type, btn) {
    const question = decodeURIComponent(btn.dataset.q);
    post('toggle_bookmark', { type, question }).then(res => renderBookmarks(res.bookmarks));
}
function escapeHtml(s){ const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }

/* =========================================================================
   TREND CHART (SVG line, redrawn from TREND array)
   ========================================================================= */
function drawTrend() {
    const svg = document.getElementById('trendSvg');
    const w = 300, h = 130, pad = 10;
    const n = TREND.length;
    const pts = TREND.map((v, i) => {
        const x = pad + (i * (w - pad * 2) / (n - 1));
        const y = h - pad - (v / 100) * (h - pad * 2);
        return [x, y];
    });
    const path = pts.map((p, i) => (i === 0 ? 'M' : 'L') + p[0].toFixed(1) + ',' + p[1].toFixed(1)).join(' ');
    const area = path + ` L${pts[pts.length - 1][0]},${h} L${pts[0][0]},${h} Z`;
    const dots = pts.map(p => `<circle cx="${p[0].toFixed(1)}" cy="${p[1].toFixed(1)}" r="3" fill="#5A4CF0"/>`).join('');
    const last = pts[pts.length - 1];
    svg.innerHTML = `
        <defs><linearGradient id="tg" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#5A4CF0" stop-opacity="0.25"/>
            <stop offset="100%" stop-color="#5A4CF0" stop-opacity="0"/>
        </linearGradient></defs>
        <path d="${area}" fill="url(#tg)"/>
        <path d="${path}" fill="none" stroke="#5A4CF0" stroke-width="2.5"/>
        ${dots}
        <text x="${last[0]-14}" y="${last[1]-10}" font-size="11" font-weight="700" fill="#5A4CF0">${TREND[TREND.length-1]}%</text>
    `;
}
drawTrend();

/* =========================================================================
   QUIZ ENGINE
   ========================================================================= */
let quizState = null;

function buildQuizQuestions(typeIds, perType) {
    const list = [];
    typeIds.forEach(id => {
        const bank = QUESTION_BANK[id] || [];
        const shuffled = [...bank].sort(() => Math.random() - 0.5).slice(0, perType);
        shuffled.forEach(q => list.push({ type: id, ...q }));
    });
    return list.sort(() => Math.random() - 0.5);
}

function startQuiz(typeId, opts = {}) {
    const questions = buildQuizQuestions([typeId], 5);
    launchQuiz(questions, TYPE_META.find(t => t.id === typeId).name, opts);
}
function startSectionalFor(macro) {
    const ids = TYPE_META.filter(t => t.macro === macro).map(t => t.id);
    const questions = buildQuizQuestions(ids, 2);
    launchQuiz(questions, macro.charAt(0).toUpperCase() + macro.slice(1) + ' Sectional Test', { onFinish: saveSectional, timerSec: 90 });
}
function startSectional() {
    const macro = document.getElementById('sectionalSelect').value;
    startSectionalFor(macro);
}
function startFullMock() {
    const ids = TYPE_META.map(t => t.id);
    const questions = buildQuizQuestions(ids, 1);
    launchQuiz(questions, 'Full Mock Test (Demo)', { onFinish: saveFullMock, timerSec: 180 });
}
function startCustom() {
    const chosen = Array.from(document.querySelectorAll('#customChkRow .chk.on')).map(c => c.dataset.key);
    if (!chosen.length) { showToast('Select at least one section'); return; }
    const ids = TYPE_META.filter(t => chosen.includes(t.macro)).map(t => t.id);
    const questions = buildQuizQuestions(ids, 1);
    launchQuiz(questions, 'Custom Mock Test', { timerSec: chosen.length * 60 });
}

document.querySelectorAll('#customChkRow .chk').forEach(chk => {
    chk.addEventListener('click', () => {
        chk.classList.toggle('on');
        const n = document.querySelectorAll('#customChkRow .chk.on').length || 1;
        document.getElementById('customTime').textContent = (n * 20) + ' min';
        document.getElementById('customQCount').textContent = (n * 10);
    });
});

function launchQuiz(questions, title, opts = {}) {
    if (!questions.length) { showToast('No questions available'); return; }
    quizState = {
        title, questions, index: 0, selected: null, answered: false,
        correctByType: {}, totalByType: {}, correctCount: 0,
        onFinish: opts.onFinish || null,
        timeLeft: opts.timerSec || null,
        timerId: null,
        bookmarked: new Set(),
    };
    document.getElementById('quizOverlay').classList.add('open');
    renderQuestion();
    if (quizState.timeLeft) startTimer();
}
function startTimer() {
    updateTimerChip();
    quizState.timerId = setInterval(() => {
        quizState.timeLeft--;
        updateTimerChip();
        if (quizState.timeLeft <= 0) { clearInterval(quizState.timerId); finishQuiz(); }
    }, 1000);
}
function updateTimerChip() {
    const el = document.getElementById('quizTimer');
    if (!el) return;
    const m = Math.floor(quizState.timeLeft / 60), s = quizState.timeLeft % 60;
    el.textContent = `⏱ ${m}:${s.toString().padStart(2, '0')} remaining`;
}
function closeQuiz() {
    if (quizState && quizState.timerId) clearInterval(quizState.timerId);
    document.getElementById('quizOverlay').classList.remove('open');
    quizState = null;
}
function renderQuestion() {
    const s = quizState;
    const q = s.questions[s.index];
    const body = document.getElementById('quizBody');
    const pctDone = Math.round((s.index / s.questions.length) * 100);
    const bmKey = q.type + '|' + q.q;
    body.innerHTML = `
        <h2>${escapeHtml(s.title)}</h2>
        <div class="sub">${escapeHtml(TYPE_META.find(t=>t.id===q.type).name)}</div>
        ${s.timeLeft ? `<div class="timer-chip" id="quizTimer">⏱ --:--</div>` : ''}
        <div class="q-progress">
            <div class="bar"><i style="width:${pctDone}%"></i></div>
            <span>${s.index + 1} / ${s.questions.length}</span>
            <button class="star-btn" id="qStar" onclick="toggleQBookmark()">★</button>
        </div>
        <div class="q-text">${escapeHtml(q.q)}</div>
        <div id="optsWrap">
            ${q.options.map((o, i) => `<button class="opt" data-i="${i}" onclick="selectOpt(${i})">${escapeHtml(o)}</button>`).join('')}
        </div>
        <div class="q-actions">
            <div class="left"></div>
            <button class="btn primary" id="nextBtn" disabled onclick="nextQuestion()">${s.index === s.questions.length - 1 ? 'Finish' : 'Next'} →</button>
        </div>
    `;
    if (s.timeLeft) updateTimerChip();
}
function selectOpt(i) {
    const s = quizState;
    if (s.answered) return;
    s.answered = true;
    s.selected = i;
    const q = s.questions[s.index];
    const isCorrect = i === q.answer;
    if (isCorrect) s.correctCount++;
    s.totalByType[q.type] = (s.totalByType[q.type] || 0) + 1;
    s.correctByType[q.type] = (s.correctByType[q.type] || 0) + (isCorrect ? 1 : 0);

    document.querySelectorAll('#optsWrap .opt').forEach((btn, idx) => {
        btn.disabled = true;
        if (idx === q.answer) btn.classList.add('correct');
        else if (idx === i) btn.classList.add('wrong');
    });
    document.getElementById('nextBtn').disabled = false;
}
function toggleQBookmark() {
    const s = quizState;
    const q = s.questions[s.index];
    const key = q.type + '|' + q.q;
    post('toggle_bookmark', { type: q.type, question: q.q }).then(res => {
        document.getElementById('qStar').classList.toggle('on', res.bookmarked);
        renderBookmarks(res.bookmarks);
        showToast(res.bookmarked ? 'Question bookmarked' : 'Bookmark removed');
    });
}
function nextQuestion() {
    const s = quizState;
    if (s.index < s.questions.length - 1) {
        s.index++;
        s.answered = false;
        s.selected = null;
        renderQuestion();
    } else {
        finishQuiz();
    }
}
function finishQuiz() {
    const s = quizState;
    if (s.timerId) clearInterval(s.timerId);
    const total = s.questions.length;
    const correct = s.correctCount;
    const results = Object.keys(s.totalByType).map(type => ({
        type, correct: s.correctByType[type] || 0, total: s.totalByType[type],
    }));

    post('submit_quiz', { results: JSON.stringify(results) }).then(data => {
        if (data.ok) applyServerSnapshot(data);
        renderResult(correct, total);
        if (s.onFinish) s.onFinish(correct, total);
    }).catch(() => renderResult(correct, total));
}
function renderResult(correct, total) {
    const pctScore = Math.round((correct / total) * 100);
    document.getElementById('quizBody').innerHTML = `
        <div class="result-box">
            <div style="font-size:34px;">${pctScore >= 70 ? '🎉' : pctScore >= 50 ? '👍' : '💪'}</div>
            <div class="big">${correct}/${total}</div>
            <p>You scored ${pctScore}% on this session. Your stats have been updated.</p>
            <div class="result-row">
                <button class="btn ghost" onclick="closeQuiz()">Close</button>
                <button class="btn primary" onclick="closeQuiz(); switchTab('attempts');">View Attempts</button>
            </div>
        </div>`;
}
function saveFullMock(correct, total) {
    const score = Math.round((correct / total) * 90);
    post('save_full_score', { score, total: 90 }).then(res => {
        document.getElementById('lastFullScore').textContent = res.last.score + ' / ' + res.last.total;
        document.getElementById('lastFullDate').textContent = res.last.date;
    });
}
function saveSectional(correct, total) {
    const score = Math.round((correct / total) * 90);
    post('save_sectional_score', { score, total: 90 }).then(res => {
        document.getElementById('lastSectionalScore').textContent = res.last.score + ' / ' + res.last.total;
        document.getElementById('lastSectionalAttempts').textContent = res.last.attempts;
    });
}

/* =========================================================================
   APPLY SERVER SNAPSHOT AFTER A QUIZ (updates dashboard without reload)
   ========================================================================= */
function applyServerSnapshot(data) {
    // overall + donut
    document.getElementById('overallAccVal').textContent = data.overall + '%';
    const ring = document.getElementById('donutRing');
    const r = 62, C = 2 * Math.PI * r;
    ring.setAttribute('stroke-dashoffset', C * (1 - data.overall / 100));

    // macro skills
    Object.keys(data.macro).forEach(k => {
        const el = document.getElementById('macro_' + k + '_val');
        if (el) el.textContent = data.macro[k] + '%';
    });

    // type cards
    Object.keys(data.types).forEach(id => {
        const accEl = document.getElementById('typeacc_' + id);
        const barEl = document.getElementById('typebar_' + id);
        if (accEl) accEl.textContent = data.types[id] + '%';
        if (barEl) barEl.style.width = data.types[id] + '%';
        const card = document.querySelector(`#typeGrid .type-card[onclick], #typeGrid .type-card`);
    });
    document.querySelectorAll('#typeGrid .type-card').forEach(card => {
        const btn = card.querySelector('.go');
        if (!btn) return;
        const id = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
        if (data.types[id] !== undefined) card.dataset.acc = data.types[id];
    });

    // weekly bars + total
    document.getElementById('weeklyTotalVal').textContent = data.weekly.reduce((a,b)=>a+b,0).toLocaleString();
    const max = Math.max(1, ...data.weekly);
    document.querySelectorAll('#weeklyBars i').forEach((bar, i) => {
        bar.style.height = Math.max(6, Math.round((data.weekly[i] / max) * 42)) + 'px';
    });

    // recent list + attempts table
    const recentWrap = document.getElementById('recentList');
    recentWrap.innerHTML = data.recent.slice(0, 4).map(r => {
        const acc = r.accuracy;
        const cls = acc >= 70 ? 'good' : (acc >= 60 ? 'mid' : 'bad');
        return `<div class="recent-item"><div class="ico">📄</div><div class="txt"><b>${escapeHtml(r.name)}</b><span>${r.count} Questions</span></div><div class="badge-acc ${cls}">${acc}%</div></div>`;
    }).join('');
    document.getElementById('attemptsBody').innerHTML = data.recent.map(r =>
        `<tr><td>${escapeHtml(r.name)}</td><td>${r.count}</td><td>${r.accuracy}%</td></tr>`).join('');

    // trend
    TREND = data.trend;
    drawTrend();
}
</script>
@endpush