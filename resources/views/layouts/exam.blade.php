{{--
|--------------------------------------------------------------------------
| schoolar.ai — CBT exam shell
|--------------------------------------------------------------------------
| A deliberately chromeless layout for the exam screens. No sidebar, no
| topbar, no UI zoom — a real CBT window fills the display, and the
| candidate should have nothing to click except the exam itself.
|
| Do NOT extend layouts.app for exam screens. The dashboard shell sets
| html{zoom:.75}, which would shrink the exam window and break the
| fixed-height palette panel.
|
| A page provides:
|   @section('title')     browser tab title
|   @section('content')   the whole screen
|   @push('styles')       page-only CSS
|   @push('scripts')      page-only JS
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="{{ $lang ?? 'en' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Computer Based Test')</title>

<style>
/* ══════════════════════════ Tokens ══════════════════════════ */
:root{
    --ex-teal:#17A2B8;        /* primary action (Submit, I am ready)   */
    --ex-teal-dk:#128298;
    --ex-soft:#A9DCEC;        /* secondary buttons (Previous, Save)    */
    --ex-soft-dk:#8CCBDF;
    --ex-panel:#DCEEF7;       /* right-hand candidate panel            */
    --ex-panel-head:#A9D6E8;
    --ex-tab:#1E7A8C;         /* active section tab                    */
    --ex-line:#D5DBE0;
    --ex-text:#212529;
    --ex-muted:#6C757D;
    --ex-link:#0B5FA5;
    --ex-danger:#DC3545;

    /* palette states — these five are the exam's whole vocabulary */
    --st-answered:#22A45D;
    --st-not-answered:#B4322C;
    --st-marked:#7C3E9B;
    --st-not-visited:#FFFFFF;

    --ex-topbar:66px;
    --ex-footer:64px;
    --ex-panel-w:360px;
}

*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{
    font-family:'Segoe UI',-apple-system,BlinkMacSystemFont,Roboto,Arial,sans-serif;
    font-size:15px;line-height:1.5;color:var(--ex-text);background:#fff;
    -webkit-font-smoothing:antialiased;
}
/* Hindi renders a little small next to Latin at the same px size. */
html[lang="hi"] body,.hi-text{font-size:15.5px;line-height:1.75}

button{font-family:inherit}
a{color:var(--ex-link)}

/* ══════════════════════════ Buttons ══════════════════════════ */
.ex-btn{border:0;border-radius:4px;padding:10px 18px;font-size:14px;font-weight:600;
        cursor:pointer;color:#fff;background:var(--ex-teal);transition:filter .12s}
.ex-btn:hover:not(:disabled){filter:brightness(1.07)}
.ex-btn:disabled{opacity:.55;cursor:not-allowed}
.ex-btn.soft{background:var(--ex-soft);color:#123}
.ex-btn.grey{background:#E9ECEF;color:#495057;border:1px solid var(--ex-line)}
.ex-btn.wide{min-width:150px}
.ex-btn.block{width:100%;display:block}

/* ══════════════════════════ Instruction pages ══════════════════════════ */
/* Two columns: the scrolling instruction sheet, and the candidate card. */
.ex-instr{display:flex;height:100vh;overflow:hidden}
.ex-instr__main{flex:1;min-width:0;display:flex;flex-direction:column;border-right:1px solid var(--ex-line)}
.ex-instr__scroll{flex:1;overflow-y:auto;padding:26px 30px 20px}
.ex-instr__foot{flex-shrink:0;border-top:1px solid var(--ex-line);background:#fff;
        padding:12px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px}
.ex-instr__aside{width:300px;flex-shrink:0;background:#F4F9FC;
        display:flex;flex-direction:column;align-items:center;padding-top:34px}

.candidate-avatar{width:112px;height:112px;border-radius:50%;background:#5D4037;color:#fff;
        display:grid;place-items:center;font-size:52px;font-weight:600;margin-bottom:18px}
.candidate-name{font-size:26px;font-weight:400;color:#212529;text-align:center;padding:0 12px}

.ex-title{font-size:30px;font-weight:700;text-align:center;margin-bottom:22px}
.ex-meta{display:flex;justify-content:space-between;font-weight:700;font-size:15px;margin-bottom:18px}
.ex-lead{font-weight:700;margin-bottom:12px}
.ex-list{margin:0 0 18px 22px}
.ex-list li{margin-bottom:11px}
.ex-list ol{margin:9px 0 9px 22px}
.ex-list ul{margin:9px 0 9px 22px}
.ex-note{color:var(--ex-danger)}
.ex-h3{font-weight:700;font-size:16px;margin:20px 0 10px}

.ex-legend{list-style:none;margin:12px 0 18px}
.ex-legend li{display:flex;align-items:center;gap:12px;margin-bottom:10px}
.legend-chip{width:30px;height:26px;flex-shrink:0;border:1px solid #6C757D;background:#fff}
.legend-chip.sq{border-radius:3px}
.legend-chip.answered{background:var(--st-answered);border-color:var(--st-answered);
        border-radius:3px 3px 8px 8px}
.legend-chip.not-answered{background:var(--st-not-answered);border-color:var(--st-not-answered);
        border-radius:8px 8px 3px 3px}
.legend-chip.marked{background:var(--st-marked);border-color:var(--st-marked);border-radius:50%}
.legend-chip.marked-answered{background:var(--st-marked);border-color:var(--st-marked);
        border-radius:50%;position:relative}
.legend-chip.marked-answered::after{content:"✓";position:absolute;right:-4px;bottom:-4px;
        width:15px;height:15px;border-radius:50%;background:var(--st-answered);color:#fff;
        font-size:10px;line-height:15px;text-align:center}

.lang-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;
        padding:14px 26px;border-top:1px solid var(--ex-line)}
.lang-row select{padding:5px 8px;font-size:14px;font-family:inherit;
        border:1px solid #767676;border-radius:2px;background:#fff}
.declare-row{display:flex;align-items:flex-start;gap:9px;padding:0 26px 4px}
.declare-row input{width:16px;height:16px;margin-top:3px;flex-shrink:0;accent-color:var(--ex-link)}
.declare-row label{font-weight:700;font-size:14.5px}

/* ══════════════════════════ Test window ══════════════════════════ */
.ex-app{height:100vh;display:flex;flex-direction:column;overflow:hidden}

.ex-top{height:var(--ex-topbar);flex-shrink:0;border-bottom:1px solid var(--ex-line);
        display:flex;align-items:center;justify-content:space-between;gap:16px;padding:0 20px}
.ex-top__name{font-size:16px;font-weight:600}
.ex-clock{display:flex;align-items:center;gap:10px;border:1px solid var(--ex-line);
        border-radius:5px;padding:7px 14px}
.ex-clock__label{font-size:16px;font-weight:700}
.ex-clock__box{background:#E9ECEF;border-radius:3px;padding:3px 7px;font-size:16px;
        font-weight:700;font-variant-numeric:tabular-nums;min-width:31px;text-align:center}
.ex-clock.warn .ex-clock__box{background:#FFE3E3;color:#B4322C}
.ex-fs{border:1px solid var(--ex-teal);background:#fff;color:var(--ex-teal);
        border-radius:4px;padding:9px 15px;font-size:14px;font-weight:600;cursor:pointer}

.ex-sections{flex-shrink:0;border-bottom:1px solid var(--ex-line);
        display:flex;align-items:stretch;gap:0;padding-left:20px;background:#fff}
.ex-sections__label{display:flex;align-items:center;font-size:13.5px;color:#495057;
        padding-right:22px;border-right:1px solid var(--ex-line);margin-right:14px}
.sec-tab{border:0;background:#E9F3F6;color:#1E7A8C;font-size:14px;font-weight:600;
        padding:11px 20px;cursor:pointer;border-radius:4px 4px 0 0;margin:6px 4px 0 0;
        white-space:nowrap}
.sec-tab[aria-selected="true"]{background:var(--ex-tab);color:#fff}

.ex-body{flex:1;display:flex;min-height:0}

/* -- question column -- */
.ex-q{flex:1;min-width:0;display:flex;flex-direction:column}
.ex-q__head{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;
        gap:18px;padding:14px 22px;border-bottom:1px solid #EEF1F3;flex-wrap:wrap}
.ex-q__no{font-size:17px;font-weight:700}
.ex-q__stats{display:flex;align-items:center;gap:22px;font-size:13px;color:#495057}
.ex-q__stats b{display:block;font-size:12.5px;color:#212529}
.mark-pos{background:var(--st-answered);color:#fff;border-radius:11px;padding:2px 9px;
        font-weight:700;font-size:12.5px}
.mark-neg{background:var(--st-not-answered);color:#fff;border-radius:11px;padding:2px 9px;
        font-weight:700;font-size:12.5px}
.ex-q__lang select{padding:4px 8px;font-size:13.5px;font-family:inherit;
        border:1px solid #767676;border-radius:3px;background:#fff}
.ex-report{border:0;background:none;color:var(--ex-muted);font-size:13.5px;cursor:pointer;
        display:inline-flex;align-items:center;gap:5px}

.ex-q__scroll{flex:1;overflow-y:auto;padding:22px}
.ex-q__text{font-size:17px;line-height:1.65;margin-bottom:22px;max-width:900px}
.ex-opts{list-style:none;max-width:900px}
.ex-opts li{margin-bottom:14px}
.ex-opt{display:flex;align-items:flex-start;gap:12px;cursor:pointer;font-size:16px;
        line-height:1.55;padding:2px 0}
.ex-opt input{width:16px;height:16px;margin-top:4px;flex-shrink:0;accent-color:var(--ex-link)}

.ex-foot{height:var(--ex-footer);flex-shrink:0;border-top:1px solid var(--ex-line);
        display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0 18px}
.ex-foot__left{display:flex;gap:10px}

/* -- right panel -- */
.ex-side{width:var(--ex-panel-w);flex-shrink:0;background:var(--ex-panel);
        border-left:1px solid #BFD8E5;display:flex;flex-direction:column}
.ex-side__who{display:flex;align-items:center;gap:11px;padding:12px 16px;background:#fff;
        border-bottom:1px solid #CFE2EC}
.ex-side__who .av{width:38px;height:38px;border-radius:50%;background:#5D4037;color:#fff;
        display:grid;place-items:center;font-size:18px;font-weight:600;flex-shrink:0}
.ex-side__who b{font-size:15px;font-weight:600}

.ex-counts{display:grid;grid-template-columns:1fr 1fr;gap:7px 12px;padding:12px 16px;
        background:#fff;border-bottom:1px solid #CFE2EC;font-size:12.5px}
.ex-counts span{display:flex;align-items:center;gap:7px}
.cnt{min-width:22px;height:20px;border-radius:3px;color:#fff;font-weight:700;font-size:11.5px;
        display:grid;place-items:center;padding:0 5px}
.cnt.answered{background:var(--st-answered)}
.cnt.marked{background:var(--st-marked)}
.cnt.not-visited{background:#fff;color:#212529;border:1px solid #6C757D}
.cnt.marked-answered{background:var(--st-marked)}
.cnt.not-answered{background:var(--st-not-answered)}

.ex-side__sec{padding:9px 16px;background:#B9DCEC;font-size:14px;font-weight:600}
.ex-palette{flex:1;overflow-y:auto;padding:14px 16px;
        display:grid;grid-template-columns:repeat(5,1fr);gap:9px;align-content:start}
.pal{height:36px;border:1px solid #6C757D;background:#fff;color:#212529;border-radius:4px;
        font-size:14px;font-weight:600;cursor:pointer;display:grid;place-items:center;
        position:relative;transition:transform .08s}
.pal:hover{transform:scale(1.06)}
.pal.current{outline:2px solid #0B5FA5;outline-offset:1px}
.pal[data-state="answered"]{background:var(--st-answered);border-color:var(--st-answered);
        color:#fff;border-radius:4px 4px 13px 13px}
.pal[data-state="not-answered"]{background:var(--st-not-answered);border-color:var(--st-not-answered);
        color:#fff;border-radius:13px 13px 4px 4px}
.pal[data-state="marked"]{background:var(--st-marked);border-color:var(--st-marked);
        color:#fff;border-radius:50%}
.pal[data-state="marked-answered"]{background:var(--st-marked);border-color:var(--st-marked);
        color:#fff;border-radius:50%}
.pal[data-state="marked-answered"]::after{content:"✓";position:absolute;right:-3px;bottom:-3px;
        width:16px;height:16px;border-radius:50%;background:var(--st-answered);color:#fff;
        font-size:10px;line-height:16px;text-align:center}

.ex-side__foot{flex-shrink:0;padding:12px 16px;display:grid;gap:9px;
        grid-template-columns:1fr 1fr;border-top:1px solid #CFE2EC}
.ex-side__foot .ex-btn.soft{padding:11px 8px;font-size:13.5px}
.ex-side__foot .submit{grid-column:1 / -1;padding:13px;font-size:15px}

/* ══════════════════════════ Modal ══════════════════════════ */
.ex-modal{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:400;
        display:none;align-items:flex-start;justify-content:center;padding:64px 16px}
.ex-modal.open{display:flex}
.ex-modal__box{background:#fff;border-radius:6px;width:100%;max-width:780px;
        box-shadow:0 12px 40px rgba(0,0,0,.3);max-height:calc(100vh - 128px);
        display:flex;flex-direction:column}
.ex-modal__head{padding:16px;text-align:center;font-size:15px;border-bottom:1px solid #EEF1F3}
.ex-modal__body{padding:18px;overflow:auto}
.ex-modal__foot{padding:14px 18px;border-top:1px solid var(--ex-line);
        display:flex;justify-content:flex-end;gap:10px}
.ex-table{width:100%;border-collapse:collapse;font-size:14px}
.ex-table th{background:var(--ex-teal);color:#fff;font-weight:700;padding:14px 10px;
        text-align:center;border:1px solid var(--ex-teal)}
.ex-table td{padding:14px 10px;text-align:center;border:1px solid var(--ex-line)}
.ex-table td:first-child{text-align:left}

/* ══════════════════════════ Toast ══════════════════════════ */
.ex-toast{position:fixed;bottom:26px;left:50%;transform:translateX(-50%) translateY(8px);
        background:#212529;color:#fff;padding:11px 20px;border-radius:6px;font-size:14px;
        font-weight:600;z-index:500;opacity:0;pointer-events:none;transition:.2s}
.ex-toast.show{opacity:1;transform:translateX(-50%)}

/* ══════════════════════════ Small screens ══════════════════════════ */
/* A real CBT centre is always a desktop. This only keeps the page usable
   if she opens it on a laptop with a narrow window. */
@media(max-width:1100px){
    :root{--ex-panel-w:290px}
    .ex-palette{grid-template-columns:repeat(4,1fr)}
}
@media(max-width:860px){
    .ex-body{flex-direction:column}
    .ex-side{width:100%;border-left:0;border-top:1px solid #BFD8E5;max-height:44vh}
    .ex-instr{flex-direction:column;height:auto;overflow:visible}
    .ex-instr__aside{width:100%;flex-direction:row;gap:16px;padding:16px;justify-content:center}
    .candidate-avatar{width:56px;height:56px;font-size:26px;margin:0}
    .candidate-name{font-size:19px}
}
</style>

@stack('styles')
</head>
<body>

@yield('content')

<div class="ex-toast" id="exToast"></div>

<script>
/* Shared helpers. Defined before page scripts so a page can call them. */
window.exToast = function (msg) {
    var t = document.getElementById('exToast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window.__exToast);
    window.__exToast = setTimeout(function () { t.classList.remove('show'); }, 2600);
};

/* Real CBT software runs full-screen. This is best-effort: browsers only
   allow it from a user gesture, so it is wired to a button, never on load. */
window.exFullscreen = function () {
    var d = document, e = d.documentElement;
    if (!d.fullscreenElement) {
        (e.requestFullscreen || e.webkitRequestFullscreen || function () {}).call(e);
    } else {
        (d.exitFullscreen || d.webkitExitFullscreen || function () {}).call(d);
    }
};
</script>

@stack('scripts')
</body>
</html>
