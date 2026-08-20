{{--
    Shared sidebar navigation.

    Only the <a> tags live here — each page keeps its own sidebar CSS, brand
    block and profile card, so every page still looks exactly as designed.

    Usage:  <nav class="nav">@include('partials.nav', ['active' => 'dashboard'])</nav>
--}}
@php($active = $active ?? '')

<a href="{{ route('dashboard') }}" @class(['active' => $active === 'dashboard'])><span class="ico">🏠</span> Dashboard</a>
<a href="{{ route('mock-tests.index') }}" @class(['active' => $active === 'mock-tests'])><span class="ico">📋</span> My Tests</a>
<a href="{{ route('practice.index') }}" @class(['active' => $active === 'practice'])><span class="ico">🎯</span> Practice</a>
<a href="{{ route('mira.index') }}" @class(['active' => $active === 'mira'])><span class="ico">✨</span> AI Tutor (Mira)</a>
<a href="{{ route('live-classes.index') }}" @class(['active' => $active === 'live-classes'])><span class="ico">📡</span> Live Classes <span class="live badge-live">LIVE</span></a>
<a href="{{ route('coming-soon', ['feature' => 'study-plan']) }}"><span class="ico">📅</span> Study Plan</a>
<a href="{{ route('coming-soon', ['feature' => 'vocabulary']) }}"><span class="ico">🔤</span> Vocabulary</a>
<a href="{{ route('coming-soon', ['feature' => 'performance']) }}"><span class="ico">📈</span> Performance</a>
<a href="{{ route('coming-soon', ['feature' => 'resources']) }}"><span class="ico">📎</span> Resources</a>
<a href="{{ route('mock-history.index') }}" @class(['active' => $active === 'mock-history'])><span class="ico">🕘</span> Mock History</a>
<a href="{{ route('coming-soon', ['feature' => 'community']) }}"><span class="ico">👥</span> Community</a>
<a href="{{ route('coming-soon', ['feature' => 'achievements']) }}"><span class="ico">🏆</span> Achievements</a>
<a href="{{ route('profile') }}" @class(['active' => $active === 'profile'])><span class="ico">⚙️</span> Settings</a>
