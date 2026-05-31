@props(['route','icon','label','badge'=>null])
<a href="{{ route($route) }}"
   onclick="closeSidebar()"
   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13.5px] no-underline transition-all duration-150
          {{ request()->routeIs($route)
             ? 'bg-green/15 text-green'
             : 'text-white/55 hover:bg-white/[0.07] hover:text-white/85' }}">
  <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
  </svg>
  <span>{{ $label }}</span>
  @if($badge)
  <span class="ml-auto bg-orange text-white text-[10px] font-semibold px-1.5 py-0.5 rounded-full">
    {{ $badge }}
  </span>
  @endif
</a>
