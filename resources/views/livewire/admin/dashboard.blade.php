
    @section('page-title', 'Dashboard')
    @section('page-subtitle', 'Visão geral do sistema')

    <div class="space-y-6">

        {{-- Banner ciclo ativo --}}
        @if($stats['ciclo_ativo'])
        <div class="flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-5 py-4">
            <span class="material-symbols-outlined text-[#006947] text-xl">play_circle</span>
            <div>
                <p class="text-[#006947] font-['Manrope'] font-bold text-sm">Ciclo ativo: {{ $stats['ciclo_ativo'] }}</p>
                <p class="text-[#005236] text-xs mt-0.5">Avaliações em andamento</p>
            </div>
        </div>
        @else
        <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
            <span class="material-symbols-outlined text-amber-600 text-xl">pause_circle</span>
            <div>
                <p class="text-amber-700 font-['Manrope'] font-bold text-sm">Nenhum ciclo ativo</p>
                <p class="text-amber-600 text-xs mt-0.5">
                    <a href="{{ route('admin.config.ciclos') }}" class="underline">Configure um ciclo</a> para iniciar as avaliações.
                </p>
            </div>
        </div>
        @endif

        {{-- Cards de métricas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['icon' => 'groups', 'label' => 'Servidores ativos', 'value' => $stats['servidores'], 'color' => 'bg-[#d8e2ff] text-[#004395]'],
                ['icon' => 'domain', 'label' => 'Áreas', 'value' => $stats['areas'], 'color' => 'bg-[#dee2f7] text-[#414657]'],
                ['icon' => 'workspace_premium', 'label' => 'Competências', 'value' => $stats['competencias'], 'color' => 'bg-[#6ffbbe] text-[#002113]'],
                ['icon' => 'assignment_turned_in', 'label' => 'Ciclo ativo', 'value' => $stats['ciclo_ativo'] ? '1' : '0', 'color' => 'bg-amber-100 text-amber-700'],
            ] as $card)
            <div class="bg-white rounded-2xl p-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)]">
                <div class="w-10 h-10 rounded-xl {{ $card['color'] }} flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-xl">{{ $card['icon'] }}</span>
                </div>
                <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $card['value'] }}</p>
                <p class="text-[#727785] text-xs mt-1">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Panorama de Gap Institucional --}}
        @if($resumoGap && $resumoGap['total'] > 0)
        <div class="bg-white rounded-2xl p-6 shadow-[0_12px_40px_rgba(23,28,31,0.06)]">
            <h3 class="font-['Manrope'] font-bold text-[#171c1f] mb-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#0058be]">area_chart</span>
                Panorama de Gap Institucional
            </h3>
            <p class="text-xs text-[#727785] mb-5">Ciclo ativo · {{ $resumoGap['total'] }} itens competência-servidor avaliados</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach([
                    ['key' => 'adequado',     'label' => 'Adequado',             'icon' => 'check_circle', 'bg' => 'bg-[#6ffbbe]/20',  'text' => 'text-[#006947]'],
                    ['key' => 'leve',         'label' => 'Desenvolvimento leve', 'icon' => 'warning',      'bg' => 'bg-amber-50',       'text' => 'text-amber-700'],
                    ['key' => 'estrategico',  'label' => 'Prioridade estratégica','icon'=> 'priority_high','bg' => 'bg-[#ffdad6]',      'text' => 'text-[#ba1a1a]'],
                    ['key' => 'sem_avaliacao','label' => 'Sem avaliação',         'icon' => 'schedule',     'bg' => 'bg-[#f0f4f8]',      'text' => 'text-[#727785]'],
                ] as $card)
                @php $n = $resumoGap[$card['key']]; $pct = $resumoGap['total'] > 0 ? round($n / $resumoGap['total'] * 100) : 0; @endphp
                <div class="rounded-xl {{ $card['bg'] }} px-4 py-3">
                    <div class="flex items-center gap-1.5 mb-2">
                        <span class="material-symbols-outlined text-lg {{ $card['text'] }}">{{ $card['icon'] }}</span>
                        <span class="text-xs font-bold {{ $card['text'] }}">{{ $card['label'] }}</span>
                    </div>
                    <p class="font-['Manrope'] font-extrabold text-2xl {{ $card['text'] }}">{{ $n }}</p>
                    <p class="text-xs {{ $card['text'] }} opacity-70 mt-0.5">{{ $pct }}% do total</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Checklist de setup (quando banco vazio) --}}
        @if($stats['areas'] === 0 || $stats['servidores'] === 0 || $stats['competencias'] === 0)
        <div class="bg-white rounded-2xl p-6 shadow-[0_12px_40px_rgba(23,28,31,0.06)]">
            <h3 class="font-['Manrope'] font-bold text-[#171c1f] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#0058be]">checklist</span>
                Configure o sistema para começar
            </h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 {{ $stats['areas'] > 0 ? 'opacity-50' : '' }}">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $stats['areas'] > 0 ? 'bg-[#6ffbbe]' : 'bg-[#f0f4f8] border-2 border-[#c2c6d6]' }}">
                        @if($stats['areas'] > 0)
                        <span class="material-symbols-outlined text-[#002113] text-sm">check</span>
                        @else
                        <span class="text-xs font-bold text-[#727785]">1</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.areas') }}" class="text-sm {{ $stats['areas'] > 0 ? 'line-through text-[#727785]' : 'text-[#0058be] font-semibold underline' }}">
                        Cadastrar áreas organizacionais
                    </a>
                </div>
                <div class="flex items-center gap-3 {{ $stats['servidores'] > 0 ? 'opacity-50' : '' }}">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $stats['servidores'] > 0 ? 'bg-[#6ffbbe]' : 'bg-[#f0f4f8] border-2 border-[#c2c6d6]' }}">
                        @if($stats['servidores'] > 0)
                        <span class="material-symbols-outlined text-[#002113] text-sm">check</span>
                        @else
                        <span class="text-xs font-bold text-[#727785]">2</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.servidores') }}" class="text-sm {{ $stats['servidores'] > 0 ? 'line-through text-[#727785]' : 'text-[#0058be] font-semibold underline' }}">
                        Cadastrar servidores
                    </a>
                </div>
                <div class="flex items-center gap-3 {{ $stats['competencias'] > 0 ? 'opacity-50' : '' }}">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $stats['competencias'] > 0 ? 'bg-[#6ffbbe]' : 'bg-[#f0f4f8] border-2 border-[#c2c6d6]' }}">
                        @if($stats['competencias'] > 0)
                        <span class="material-symbols-outlined text-[#002113] text-sm">check</span>
                        @else
                        <span class="text-xs font-bold text-[#727785]">3</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.competencias') }}" class="text-sm {{ $stats['competencias'] > 0 ? 'line-through text-[#727785]' : 'text-[#0058be] font-semibold underline' }}">
                        Cadastrar competências com itens de avaliação
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $stats['ciclo_ativo'] ? 'bg-[#6ffbbe]' : 'bg-[#f0f4f8] border-2 border-[#c2c6d6]' }}">
                        @if($stats['ciclo_ativo'])
                        <span class="material-symbols-outlined text-[#002113] text-sm">check</span>
                        @else
                        <span class="text-xs font-bold text-[#727785]">4</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.config.ciclos') }}" class="text-sm {{ $stats['ciclo_ativo'] ? 'line-through text-[#727785]' : 'text-[#0058be] font-semibold underline' }}">
                        Criar e ativar um ciclo de avaliação
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>

