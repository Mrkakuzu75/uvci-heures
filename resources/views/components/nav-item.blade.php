@props(['route','icon','label','badge'=>null])
<a href="{{ route($route) }}"
   onclick="closeSidebar()"
   class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}">
  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
  </svg>
  <span>{{ $label }}</span>
  @if($badge)<span class="badge">{{ $badge }}</span>@endif
</a>
