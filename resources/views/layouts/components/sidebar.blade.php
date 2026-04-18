@php
    $servidor = auth()->user()?->servidor;
    $perfil = $servidor?->perfil ?? 'servidor';

    $menus = [
        'admin' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',     'route' => 'admin.dashboard'],
            ['icon' => 'groups',              'label' => 'Servidores',    'route' => 'admin.servidores'],
            ['icon' => 'domain',              'label' => 'Áreas',         'route' => 'admin.areas'],
            ['icon' => 'workspace_premium',   'label' => 'Competências',  'route' => 'admin.competencias'],
            ['icon' => 'assignment_turned_in','label' => 'Avaliações',    'route' => 'admin.avaliacoes'],
            ['icon' => 'analytics',           'label' => 'Relatórios',    'route' => 'admin.relatorios'],
            ['icon' => 'settings',            'label' => 'Configurações', 'route' => 'admin.config.geral'],
        ],
        'gestor' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',     'route' => 'gestor.dashboard'],
            ['icon' => 'assignment_turned_in','label' => 'Avaliações',    'route' => 'gestor.avaliacoes'],
            ['icon' => 'gavel',               'label' => 'Contestações',  'route' => 'gestor.contestacoes'],
            ['icon' => 'analytics',           'label' => 'Relatórios',    'route' => 'gestor.relatorios'],
        ],
        'servidor' => [
            ['icon' => 'dashboard',           'label' => 'Dashboard',     'route' => 'servidor.dashboard'],
            ['icon' => 'assignment_turned_in','label' => 'Avaliações',    'route' => 'servidor.avaliacoes'],
            ['icon' => 'history',             'label' => 'Histórico',     'route' => 'servidor.historico'],
        ],
    ];

    $itens = $menus[$perfil] ?? $menus['servidor'];
@endphp

<aside class="hidden md:flex bg-[#1a1f2e] h-screen w-64 fixed left-0 top-0 flex-col py-6 z-50">

    {{-- Logo --}}
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-lg">workspace_premium</span>
            </div>
            <div>
                <p class="text-white font-['Manrope'] font-bold text-sm leading-none">SGC</p>
                <p class="text-slate-400 text-xs mt-0.5">Gestão de Competências</p>
            </div>
        </div>
    </div>

    {{-- Perfil do usuário --}}
    @if($servidor)
    <div class="px-4 mb-6">
        <div class="bg-white/5 rounded-xl px-4 py-3">
            <p class="text-white text-sm font-semibold truncate">{{ $servidor->nome }}</p>
            <p class="text-slate-400 text-xs truncate">{{ $servidor->cargo }}</p>
            <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full
                @if($perfil === 'admin') bg-[#d8e2ff] text-[#004395]
                @elseif($perfil === 'gestor') bg-[#dee2f7] text-[#414657]
                @else bg-[#6ffbbe] text-[#002113] @endif
                font-semibold">
                {{ ucfirst($perfil) }}
            </span>
        </div>
    </div>
    @endif

    {{-- Navegação --}}
    <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
        @foreach($itens as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-['Manrope'] font-semibold text-sm
                   {{ request()->routeIs($item['route'].'*')
                       ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500 rounded-r-none'
                       : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Meu perfil + Logout --}}
    <div class="px-4 mt-4 space-y-1">
        <a href="{{ route('meu-perfil') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-sm font-semibold
               {{ request()->routeIs('meu-perfil')
                   ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500 rounded-r-none'
                   : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
            <span class="material-symbols-outlined text-xl">manage_accounts</span>
            <span>Meu Perfil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all text-sm font-semibold">
                <span class="material-symbols-outlined text-xl">logout</span>
                <span>Sair</span>
            </button>
        </form>
    </div>
</aside>
