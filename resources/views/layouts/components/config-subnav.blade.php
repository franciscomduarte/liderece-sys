@php
$links = [
    ['route' => 'admin.config.geral',         'icon' => 'tune',              'label' => 'Geral'],
    ['route' => 'admin.config.ciclos',         'icon' => 'event',             'label' => 'Ciclos'],
    ['route' => 'admin.config.notificacoes',   'icon' => 'notifications',     'label' => 'Notificações'],
    ['route' => 'admin.config.seguranca',      'icon' => 'security',          'label' => 'Segurança'],
    ['route' => 'admin.config.dados',          'icon' => 'database',          'label' => 'Dados'],
];
@endphp

<nav class="flex gap-1 flex-wrap bg-white rounded-2xl px-3 py-2 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
    @foreach($links as $link)
    <a href="{{ route($link['route']) }}"
       class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold transition-all
           {{ request()->routeIs($link['route'])
               ? 'bg-[#0058be] text-white shadow-md shadow-[#0058be]/20'
               : 'text-[#424754] hover:bg-[#f0f4f8] hover:text-[#171c1f]' }}">
        <span class="material-symbols-outlined text-base">{{ $link['icon'] }}</span>
        {{ $link['label'] }}
    </a>
    @endforeach
</nav>
