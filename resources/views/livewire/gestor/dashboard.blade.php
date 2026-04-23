@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral da sua área')

<div>
<div class="space-y-6">

    {{-- Banner ciclo --}}
    @if($stats['ciclo'])
    <div class="float-in flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-[#006947] text-xl">play_circle</span>
        <div class="flex-1">
            <p class="text-[#006947] font-['Manrope'] font-bold text-sm">Ciclo ativo: {{ $stats['ciclo']->nome }}</p>
            <p class="text-[#005236] text-xs mt-0.5">
                {{ $stats['ciclo']->data_inicio->format('d/m/Y') }} — {{ $stats['ciclo']->data_fim->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route('gestor.avaliacoes') }}"
           class="shrink-0 text-xs font-bold text-[#006947] underline hover:no-underline">
            Ver avaliações
        </a>
    </div>
    @else
    <div class="float-in flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-amber-600 text-xl">pause_circle</span>
        <p class="text-amber-700 font-['Manrope'] font-bold text-sm">Nenhum ciclo ativo no momento</p>
    </div>
    @endif

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="w-10 h-10 rounded-xl bg-[#d8e2ff] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#004395] text-lg">group</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['total_servidores'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Servidores na área</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-amber-600 text-lg">pending_actions</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['avaliacoes_pendentes'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Avaliações em aberto</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:150ms">
            <div class="w-10 h-10 rounded-xl bg-[#6ffbbe]/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#006947] text-lg">task_alt</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['avaliacoes_enviadas'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Avaliações concluídas</p>
        </div>
    </div>

    {{-- Gap da equipe: resumo por servidor --}}
    @if($gapsDaEquipe->isNotEmpty())
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:200ms">
        <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-[#d8e2ff] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[#004395] text-lg">area_chart</span>
            </div>
            <div class="flex-1">
                <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Gap da Equipe</h3>
                <p class="text-xs text-[#727785]">Diagnóstico por servidor — Nível esperado vs. atual</p>
            </div>
        </div>
        <div class="divide-y divide-[#eaeef2]">
            @foreach($gapsDaEquipe as $entry)
            @php
                $r = $entry['resumo'];
                $total = $r['adequado'] + $r['leve'] + $r['estrategico'] + $r['sem_avaliacao'];
            @endphp
            <div class="px-5 py-3 flex items-center gap-4 hover:bg-[#f6fafe] transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($entry['servidor']->nome, 0, 2)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $entry['servidor']->nome }}</p>
                    <p class="text-xs text-[#727785]">{{ $total }} competência(s)</p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    @if($r['adequado'] > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-[#6ffbbe]/20 text-[#006947]">
                        <span class="material-symbols-outlined text-sm">check_circle</span>{{ $r['adequado'] }}
                    </span>
                    @endif
                    @if($r['leve'] > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700">
                        <span class="material-symbols-outlined text-sm">warning</span>{{ $r['leve'] }}
                    </span>
                    @endif
                    @if($r['estrategico'] > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-[#ffdad6] text-[#ba1a1a]">
                        <span class="material-symbols-outlined text-sm">priority_high</span>{{ $r['estrategico'] }}
                    </span>
                    @endif
                    @if($r['sem_avaliacao'] > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold bg-[#f0f4f8] text-[#727785]">
                        <span class="material-symbols-outlined text-sm">schedule</span>{{ $r['sem_avaliacao'] }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Gap do próprio gestor --}}
    @if($gapsGestor->isNotEmpty())
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:250ms">
        <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-[#dee2f7] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[#414657] text-lg">person_pin</span>
            </div>
            <div>
                <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Meu Diagnóstico</h3>
                <p class="text-xs text-[#727785]">Seu perfil de competências no ciclo atual</p>
            </div>
        </div>
        <div class="divide-y divide-[#eaeef2]">
            @foreach($gapsGestor as $item)
            @php
                [$gapBg, $gapText, $gapIcon] = match($item['classificacao']) {
                    'adequado'    => ['bg-[#6ffbbe]/20', 'text-[#006947]', 'check_circle'],
                    'leve'        => ['bg-amber-50',     'text-amber-700', 'warning'],
                    'estrategico' => ['bg-[#ffdad6]',    'text-[#ba1a1a]', 'priority_high'],
                    default       => ['bg-[#f0f4f8]',    'text-[#727785]', 'schedule'],
                };
            @endphp
            <div class="px-5 py-3 flex items-center gap-4 hover:bg-[#f6fafe] transition-colors">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $item['competencia']->nome }}</p>
                    <p class="text-xs text-[#727785] mt-0.5">
                        Esperado: Nível {{ $item['nivel_esperado'] }} ({{ \App\Services\GapService::descricaoNivel($item['nivel_esperado']) }})
                        @if($item['nivel_atual'] !== null)
                            · Atual: Nível {{ $item['nivel_atual'] }} ({{ \App\Services\GapService::descricaoNivel($item['nivel_atual']) }})
                        @endif
                    </p>
                </div>
                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold {{ $gapBg }} {{ $gapText }}">
                    <span class="material-symbols-outlined text-sm">{{ $gapIcon }}</span>
                    @if($item['gap'] === null) Pendente
                    @elseif($item['gap'] === 0) Adequado
                    @else Gap +{{ $item['gap'] }}
                    @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Servidores da área --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:250ms">
        <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center justify-between">
            <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Servidores da área</h3>
            <a href="{{ route('gestor.avaliacoes') }}" class="text-xs font-semibold text-[#0058be] hover:underline">Ver tudo</a>
        </div>
        @if($servidores->isNotEmpty())
        <ul class="divide-y divide-[#eaeef2]">
            @foreach($servidores as $s)
            @php
                $av = $s->avaliacoes->first();
                $status = $av?->status ?? null;
            @endphp
            <li class="px-5 py-3 flex items-center justify-between hover:bg-[#f6fafe] transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shrink-0">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($s->nome, 0, 2)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $s->nome }}</p>
                        <p class="text-xs text-[#727785] truncate">{{ $s->cargo }}</p>
                    </div>
                </div>
                @if($stats['ciclo'])
                <span class="shrink-0 ml-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                    {{ $status === 'enviada' ? 'bg-[#6ffbbe]/20 text-[#006947]' : ($status === 'rascunho' ? 'bg-amber-50 text-amber-700' : 'bg-[#f0f4f8] text-[#727785]') }}">
                    {{ $status === 'enviada' ? 'Concluída' : ($status === 'rascunho' ? 'Em andamento' : 'Não iniciada') }}
                </span>
                @endif
            </li>
            @endforeach
        </ul>
        @else
        <div class="px-5 py-10 text-center">
            <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">group_off</span>
            <p class="text-sm text-[#727785] mt-2">Nenhum servidor na sua área.</p>
        </div>
        @endif
    </div>

</div>
</div>
