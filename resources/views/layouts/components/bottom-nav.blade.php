@php
    $servidor = auth()->user()?->servidor;
    $perfil = $servidor?->perfil ?? 'servidor';

    $menus = [
        'admin' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',    'route' => 'admin.dashboard'],
            ['icon' => 'groups',              'label' => 'Servidores',   'route' => 'admin.servidores'],
            ['icon' => 'workspace_premium',   'label' => 'Competências', 'route' => 'admin.competencias'],
            ['icon' => 'analytics',           'label' => 'Relatórios',   'route' => 'admin.relatorios'],
            ['icon' => 'settings',            'label' => 'Config.',      'route' => 'admin.config.geral'],
        ],
        'gestor' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',  'route' => 'gestor.dashboard'],
            ['icon' => 'assignment_turned_in','label' => 'Avaliações', 'route' => 'gestor.avaliacoes'],
            ['icon' => 'gavel',               'label' => 'Contestações','route' => 'gestor.contestacoes'],
            ['icon' => 'analytics',           'label' => 'Relatórios', 'route' => 'gestor.relatorios'],
        ],
        'servidor' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',  'route' => 'servidor.dashboard'],
            ['icon' => 'assignment_turned_in','label' => 'Avaliações', 'route' => 'servidor.avaliacoes'],
            ['icon' => 'history',             'label' => 'Histórico',  'route' => 'servidor.historico'],
        ],
    ];

    $itens = $menus[$perfil] ?? $menus['servidor'];
@endphp

<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[#eaeef2] z-50 shadow-[0_-4px_20px_rgba(23,28,31,0.06)]">
    <div class="flex items-center justify-around px-2 py-2">
        @foreach($itens as $item)
            <a href="{{ route($item['route']) }}"
               class="flex flex-col items-center gap-1 px-3 py-1 rounded-xl transition-all min-w-0
                   {{ request()->routeIs($item['route'].'*')
                       ? 'text-[#0058be]'
                       : 'text-[#727785]' }}">
                <span class="material-symbols-outlined text-2xl">{{ $item['icon'] }}</span>
                <span class="text-[10px] font-semibold truncate max-w-12 text-center">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
