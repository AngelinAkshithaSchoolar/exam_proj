{{--
|--------------------------------------------------------------------------
| schoolar.ai — master layout
|--------------------------------------------------------------------------
| Owns the sidebar and the topbar. Every page extends this, so the shell is
| identical everywhere and only the content area changes.
|
| A page provides:
|   @section('title')        browser tab title
|   @section('page-title')   the <h1> in the topbar
|   @section('page-sub')     the line under it
|   @section('content')      the page body
|   @push('styles')          page-only CSS  (auto-scoped to .page-content)
|   @push('scripts')         page-only JS
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'schoolar.ai')</title>

<style>
/* ══════════════════════════ Base ══════════════════════════ */
/* ══════════════════════════════════════════════════════════════════════
   UI SCALE
   ---------------------------------------------------------------------
   This design was drawn at ~1536 CSS px wide. On a display running
   Windows scaling at 150%, a 1920px screen only reports ~1280 CSS px,
   which is too narrow — cards wrap and the layout breaks up.

   Scaling the root to 0.75 makes a 1280px viewport behave like ~1706px,
   which is what you get by pressing Ctrl+Minus to 75% — except now it is
   the default and nobody has to touch the zoom control.

   To change it, edit BOTH values below — they are a pair:
       --ui-scale   1      0.9      0.8     0.75     0.675
       --ui-scale-inv  1   1.1112   1.25    1.3334   1.4815   <- 1 / scale
   ══════════════════════════════════════════════════════════════════════ */
:root{
    --ui-scale:.75;        /* the scale itself                    */
    --ui-scale-inv:1.3334; /* MUST equal 1 / --ui-scale           */
}
html{zoom:var(--ui-scale)}

/* zoom shrinks viewport units too, so 100vh would only cover 75% of the
   window. --full-h cancels that out: 100vh x 1.3334 x 0.75 = one screen. */
:root{--full-h:calc(100vh * var(--ui-scale-inv))}

*{margin:0;padding:0;box-sizing:border-box}
:root{
    --bg:#F4F6FB; --card:#FFFFFF; --text:#12142B; --muted:#767B94;
    --border:#EBEDF5; --indigo:#5A4CF0; --indigo-dark:#4636D6;
    --green:#12B76A; --red:#F0446E; --orange:#F7931E; --navy:#0E1030;
    --blue:#1677F1; --teal:#08A899;
    --shadow:0 1px 2px rgba(20,20,50,.04),0 8px 24px rgba(20,20,50,.05);
    --shell-sidebar:216px; --shell-topbar:74px;
}
html,body{min-height:var(--full-h)}
body{font-family:'Segoe UI',-apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif;
     font-size:14px;              /* base scale every page was built against */
     background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased}

/* ══════════════════════════ Shell ══════════════════════════ */
.app{display:flex;min-height:var(--full-h)}

/* ---------- Sidebar ---------- */
.sidebar{width:var(--shell-sidebar);flex-shrink:0;background:var(--navy);color:#C6C9DE;
         display:flex;flex-direction:column;padding:18px 12px;position:fixed;
         left:0;top:0;height:var(--full-h);z-index:100;overflow-y:auto}
.sidebar::-webkit-scrollbar{width:6px}
.sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.14);border-radius:99px}
.brand{display:flex;align-items:center;gap:9px;font-weight:800;color:#fff;
       font-size:16.5px;margin-bottom:18px;padding:0 6px;flex-shrink:0}
.brand .logo{width:30px;height:30px;border-radius:9px;background:var(--indigo);
             display:grid;place-items:center;font-size:15px;font-weight:800;color:#fff}
.nav{display:flex;flex-direction:column;gap:2px}
.nav a{display:flex;align-items:center;gap:10px;padding:8.5px 10px;border-radius:9px;
       color:#C6C9DE;text-decoration:none;font-size:13.5px;font-weight:500;
       white-space:nowrap;transition:background .15s,color .15s}
.nav a:hover{background:rgba(255,255,255,.07);color:#fff}
.nav a.active{background:var(--indigo);color:#fff;font-weight:700}
.nav .ico{width:18px;text-align:center;font-size:14px;flex-shrink:0}
.nav .live,.nav .badge-live{margin-left:auto;background:var(--red);color:#fff;font-size:8.5px;
       font-weight:800;padding:2.5px 6px;border-radius:5px;letter-spacing:.5px}
.upgrade{margin-top:14px;background:linear-gradient(135deg,var(--indigo),#7C4DFF);
         border-radius:13px;padding:14px 13px;flex-shrink:0}
.upgrade h4{color:#fff;font-size:13px;font-weight:800;margin-bottom:5px}
.upgrade p{color:rgba(255,255,255,.82);font-size:11px;line-height:1.45;margin-bottom:10px}
.upgrade ul{list-style:none;margin-bottom:10px}
.upgrade li{color:rgba(255,255,255,.86);font-size:10.5px;line-height:1.7}
.upgrade button{width:100%;background:#fff;color:var(--indigo-dark);border:0;border-radius:9px;
       padding:9px;font-size:12px;font-weight:800;cursor:pointer;font-family:inherit}
.upgrade button:hover{background:#F1EEFF}
.profile-mini{margin-top:12px;display:flex;align-items:center;gap:9px;padding:8px 6px;
       border-radius:10px;cursor:pointer;flex-shrink:0}
.profile-mini:hover{background:rgba(255,255,255,.07)}
.profile-mini img{width:34px;height:34px;border-radius:50%;flex-shrink:0}
.profile-mini .who{min-width:0}
.profile-mini .who b{display:block;color:#fff;font-size:12.5px;font-weight:700;
       overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.profile-mini .who span{display:block;color:#8E93AE;font-size:10.5px;
       overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

/* ---------- Main column ---------- */
.main{flex:1;min-width:0;margin-left:var(--shell-sidebar);
      display:flex;flex-direction:column;min-height:var(--full-h)}
.topbar{height:var(--shell-topbar);flex-shrink:0;position:sticky;top:0;z-index:60;
        background:rgba(244,246,251,.92);backdrop-filter:blur(8px);
        border-bottom:1px solid var(--border);
        display:flex;align-items:center;justify-content:space-between;gap:16px;padding:0 26px}
.topbar h1{font-size:22px;font-weight:800;letter-spacing:-.3px;line-height:1.2}
.topbar p,.topbar .subtitle{font-size:12.5px;color:var(--muted);margin-top:2px}
.mobile-menu{display:none;width:34px;height:34px;border:1px solid var(--border);
        background:#fff;border-radius:9px;font-size:15px;cursor:pointer;flex-shrink:0}
.top-actions{display:flex;align-items:center;gap:9px;flex-shrink:0}
.rel{position:relative}
.pill-select{background:#fff;border:1px solid var(--border);border-radius:11px;
        padding:9px 13px;font-size:12.5px;font-weight:700;cursor:pointer;
        font-family:inherit;color:var(--text);white-space:nowrap}
.pill-select:hover{border-color:var(--indigo)}
.icon-btn{width:38px;height:38px;border:1px solid var(--border);background:#fff;
        border-radius:11px;font-size:15px;cursor:pointer;display:grid;place-items:center;
        position:relative;flex-shrink:0}
.icon-btn:hover{border-color:var(--indigo)}
.icon-btn .badge{position:absolute;top:-5px;right:-5px;background:var(--red);color:#fff;
        font-size:9.5px;font-weight:800;min-width:17px;height:17px;border-radius:99px;
        display:grid;place-items:center;padding:0 4px;border:2px solid var(--bg)}
.streak-chip{display:flex;align-items:center;gap:6px;background:#fff;
        border:1px solid var(--border);padding:9px 14px;border-radius:11px;
        font-weight:800;font-size:12.5px;white-space:nowrap}
.avatar-btn{border:0;background:transparent;cursor:pointer;padding:0;flex-shrink:0}
.avatar-btn img{width:38px;height:38px;border-radius:50%;display:block}

/* ---------- Dropdowns (shared) ---------- */
.dropdown,.test-dropdown{position:absolute;top:calc(100% + 8px);right:0;min-width:190px;
        background:#fff;border:1px solid var(--border);border-radius:13px;
        box-shadow:0 14px 38px rgba(20,20,50,.14);padding:6px;z-index:300;
        opacity:0;visibility:hidden;transform:translateY(-6px);transition:.15s}
.dropdown.left,.test-dropdown.left{right:auto;left:0}
.dropdown.open,.test-dropdown.open{opacity:1;visibility:visible;transform:translateY(0)}
.sidebar .dropdown{bottom:calc(100% + 8px);top:auto;left:0;right:auto;width:200px}
.dropdown .dhead{display:flex;justify-content:space-between;align-items:center;
        padding:8px 10px;font-size:11.5px;font-weight:800;color:var(--muted);
        text-transform:uppercase;letter-spacing:.4px}
.dropdown .dhead a{color:var(--indigo);font-size:10.5px;text-decoration:none;text-transform:none}
.dropdown .ditem{padding:8px 10px;border-radius:8px;cursor:pointer}
.dropdown .ditem:hover{background:#F6F7FB}
.dropdown .ditem b{display:block;font-size:12px;font-weight:600;line-height:1.35}
.dropdown .ditem span{display:block;font-size:10.5px;color:var(--muted);margin-top:2px}
.dlink,.test-dropdown button{display:flex;align-items:center;gap:8px;width:100%;
        padding:9px 11px;border-radius:8px;border:0;background:none;text-align:left;
        font-size:12.5px;font-family:inherit;color:var(--text);cursor:pointer;text-decoration:none}
.dlink:hover,.test-dropdown button:hover{background:#F6F7FB}

/* ---------- Content well ---------- */
.page-content{flex:1;min-width:0;padding:26px 30px 60px}

/* ---------- Toast ---------- */
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%);
       background:#12142B;color:#fff;padding:12px 20px;border-radius:12px;font-size:13px;
       font-weight:700;z-index:900;opacity:0;pointer-events:none;transition:.22s}
.toast.show{opacity:1;transform:translateX(-50%) translateY(-6px)}

/* ---------- Responsive ---------- */
@media(max-width:1180px){
    .sidebar{transform:translateX(-100%);transition:transform .2s}
    .sidebar.open{transform:translateX(0)}
    .main{margin-left:0}
    .mobile-menu{display:grid;place-items:center}
}
@media(max-width:940px){
    .topbar{padding:0 15px;gap:10px}
    .topbar h1{font-size:16.5px}
    .topbar p,.topbar .subtitle{display:none}
    .streak-chip,.pill-select{display:none}
    .page-content{padding:16px 15px 26px}
}
</style>

@stack('styles')
</head>
<body>

<div class="app">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <aside class="sidebar" id="sidebar">
        <div class="brand"><div class="logo">S</div> schoolar.ai</div>

        <nav class="nav">
@include('partials.nav', ['active' => $active ?? ''])
        </nav>

        <div class="upgrade">
            <h4>👑 Upgrade to Pro</h4>
            <ul>
                <li>✔ Unlimited practice</li>
                <li>✔ Full mock tests</li>
                <li>✔ Advanced analytics</li>
                <li>✔ Priority support</li>
            </ul>
            <button type="button" onclick="showToast('Pro checkout coming soon')">Upgrade Now →</button>
        </div>

        <div class="profile-mini rel" id="profileBtn">
            <img src="https://i.pravatar.cc/80?img=12" alt="Student profile">
            <div class="who"><b>Arjun Sharma</b><span>View Profile ▾</span></div>
            <div class="dropdown" id="profileDropdown">
                <a class="dlink" href="{{ route('profile') }}">👤 View Profile</a>
                <a class="dlink" href="{{ route('profile') }}">⚙️ Settings</a>
                <button type="button" class="dlink" onclick="document.getElementById('logoutForm').submit()">🚪 Log Out</button>
            </div>
        </div>

        <form id="logoutForm" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>
    </aside>

    {{-- ═══════════════ MAIN ═══════════════ --}}
    <main class="main">

        <div class="topbar">
            <div style="display:flex;align-items:center;gap:10px;min-width:0">
                <button type="button" class="mobile-menu" id="mobileMenu" aria-label="Open navigation">☰</button>
                <div style="min-width:0">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-sub', "Let's make today a step closer to your target score.")</p>
                </div>
            </div>

            <div class="top-actions">
                <div class="rel">
                    <button type="button" class="pill-select" id="testPillBtn"><span id="testPillLabel">PTE Academic</span> ▾</button>
                    <div class="test-dropdown" id="testPillDropdown">
                        @foreach (['PTE Academic','IELTS Academic','TOEFL iBT','Duolingo English Test','GRE General Test'] as $exam)
                            <button type="button" onclick="switchTest('{{ $exam }}')">{{ $exam }}</button>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="icon-btn" title="Calendar"
                        onclick="showToast('Calendar opened — no events due today')">📅</button>

                <div class="rel">
                    <button type="button" class="icon-btn" id="bellBtn" title="Notifications">🔔<span class="badge" id="bellBadge">{{ session('unread_notifications', 6) }}</span></button>
                    <div class="dropdown" id="bellDropdown" style="width:300px">
                        <div class="dhead">Notifications <a href="#" onclick="markAllRead(event)">Mark all read</a></div>
                        <div id="notificationItems">
                            @foreach ($sampleNotifications ?? [
                                ['title' => 'New Sectional Test unlocked',                'time' => '2h ago'],
                                ['title' => 'Your Repeat Sentence accuracy dropped 8%',   'time' => '5h ago'],
                                ['title' => 'Mira suggests a Focus Practice session',     'time' => '1d ago'],
                                ['title' => 'Streak milestone: 12 days in a row 🔥',      'time' => '1d ago'],
                                ['title' => 'Weekly performance report is ready',         'time' => '2d ago'],
                                ['title' => 'Live Class starting soon',                   'time' => '3d ago'],
                            ] as $n)
                                <div class="ditem"><b>{{ $n['title'] }}</b><span>{{ $n['time'] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="streak-chip">🔥 <span id="streakChipCount">{{ session('streak_count', 12) }}</span> Day Streak</div>

                <button type="button" class="rel avatar-btn" id="avatarBtn">
                    <img src="https://i.pravatar.cc/80?img=12" alt="Profile">
                </button>
            </div>
        </div>

        <div class="page-content">
            @yield('content')
        </div>
    </main>
</div>

<div class="toast" id="toast"></div>

<script>
/* ── Shell behaviour. Defined before page scripts so a page can override. ── */
window.showToast = function (msg) {
    var t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(function () { t.classList.remove('show'); }, 2600);
};
window.switchTest = function (name) {
    var l = document.getElementById('testPillLabel');
    if (l) l.textContent = name;
    document.getElementById('testPillDropdown').classList.remove('open');
    showToast('Switched to ' + name);
};
window.markAllRead = function (e) {
    if (e) e.preventDefault();
    var b = document.getElementById('bellBadge');
    if (b) b.style.display = 'none';
    showToast('All notifications marked as read');
};
window.toggleTestDropdown = function () {
    document.getElementById('testPillDropdown').classList.toggle('open');
};

(function () {
    function toggle(btnId, ddId) {
        var b = document.getElementById(btnId), d = document.getElementById(ddId);
        if (!b || !d) return;
        b.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = d.classList.contains('open');
            document.querySelectorAll('.dropdown.open,.test-dropdown.open')
                    .forEach(function (x) { x.classList.remove('open'); });
            if (!open) d.classList.add('open');
        });
    }
    toggle('testPillBtn', 'testPillDropdown');
    toggle('bellBtn',     'bellDropdown');
    toggle('profileBtn',  'profileDropdown');
    toggle('avatarBtn',   'profileDropdown');

    var menu = document.getElementById('mobileMenu'), sb = document.getElementById('sidebar');
    if (menu && sb) {
        menu.addEventListener('click', function (e) { e.stopPropagation(); sb.classList.toggle('open'); });
    }

    document.addEventListener('click', function (e) {
        document.querySelectorAll('.dropdown.open,.test-dropdown.open').forEach(function (d) {
            if (!d.parentElement.contains(e.target)) d.classList.remove('open');
        });
        if (window.innerWidth <= 1180 && sb && !sb.contains(e.target) && e.target !== menu) {
            sb.classList.remove('open');
        }
    });
})();
</script>

@stack('scripts')
</body>
</html>
