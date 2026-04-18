@section('page-title', 'Dashboard')
@section('page-subtitle', 'Seu painel de avaliações')

<div>
<div class="space-y-6">

    {{-- Banner ciclo --}}
    @if($stats['ciclo'])
    <div class="float-in flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-[#006947] text-xl">play_circle</span>
        <div class="flex-1">
            <p class="text-[#006947] font-['Manrope'] font-bold text-sm">Ciclo ativo: {{ $stats['ciclo']->nome }}</p>
            <p class="text-[#005236] text-xs mt-0.5">
                Até {{ $stats['ciclo']->data_fim->format('d/m/Y') }}
            </p>
        </div>
        @if($stats['autoavaliacoes_pendentes'] > 0)
        <a href="{{ route('servidor.avaliacoes') }}"
           class="shrink-0 flex items-center gap-1 text-xs font-bold text-[#006947] underline hover:no-underline">
            Avaliar agora
        </a>
        @endif
    </div>
    @else
    <div class="float-in flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-amber-600 text-xl">pause_circle</span>
        <p class="text-amber-700 font-['Manrope'] font-bold text-sm">Nenhum ciclo ativo no momento</p>
    </div>
    @endif

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-amber-600 text-lg">edit_note</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['autoavaliacoes_pendentes'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Autoavaliações pendentes</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
            <div class="w-10 h-10 rounded-xl bg-[#6ffbbe]/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#006947] text-lg">assignment_turned_in</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['resultados_disponiveis'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Resultados disponíveis</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:150ms">
            <div class="w-10 h-10 rounded-xl bg-[#d8e2ff] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#004395] text-lg">notifications</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['notificacoes_nao_lidas'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Notificações não lidas</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:200ms">
            <div class="w-10 h-10 rounded-xl bg-[#ffdad6] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#93000a] text-lg">gavel</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $stats['contestacoes_pendentes'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Contestações aguardando</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Autoavaliações pendentes --}}
        <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:250ms">
            <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center justify-between">
                <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Autoavaliações pendentes</h3>
                <a href="{{ route('servidor.avaliacoes') }}" class="text-xs font-semibold text-[#0058be] hover:underline">Ver todas</a>
            </div>
            @if($autoavaliacoesPendentes->isNotEmpty())
            <ul class="divide-y divide-[#eaeef2]">
                @foreach($autoavaliacoesPendentes as $av)
                <li class="px-5 py-3.5 flex items-center justify-between hover:bg-[#f6fafe] transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $av->competencia->nome }}</p>
                        @php $tipo = $av->competencia->tipo; @endphp
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold
                            {{ $tipo === 'comportamental' ? 'bg-[#d8e2ff] text-[#004395]' : ($tipo === 'técnica' ? 'bg-[#6ffbbe]/20 text-[#002113]' : 'bg-[#dee2f7] text-[#414657]') }}">
                            {{ ucfirst($tipo) }}
                        </span>
                    </div>
                    <a href="{{ route('servidor.avaliacoes.form', $av) }}"
                       class="shrink-0 ml-3 flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-md shadow-[#0058be]/20 hover:scale-[1.02] transition-all">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        Avaliar
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-5 py-10 text-center">
                <span class="material-symbols-outlined text-3xl text-[#6ffbbe]">task_alt</span>
                <p class="text-sm font-semibold text-[#006947] mt-2">Tudo em dia!</p>
                <p class="text-xs text-[#727785] mt-1">Nenhuma autoavaliação pendente.</p>
            </div>
            @endif
        </div>

        {{-- Últimos resultados --}}
        <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:300ms">
            <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center justify-between">
                <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Últimos resultados</h3>
                <a href="{{ route('servidor.historico') }}" class="text-xs font-semibold text-[#0058be] hover:underline">Ver histórico</a>
            </div>
            @if($resultadosRecentes->isNotEmpty())
            <ul class="divide-y divide-[#eaeef2]">
                @foreach($resultadosRecentes as $av)
                <li class="px-5 py-3.5 hover:bg-[#f6fafe] transition-colors">
                    <a href="{{ route('servidor.avaliacoes.resultado', $av) }}" class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $av->competencia->nome }}</p>
                            <p class="text-xs text-[#727785]">{{ $av->ciclo->nome }} · {{ $av->enviada_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($av->contestacao)
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-amber-50 text-amber-700">Contestada</span>
                            @endif
                            <span class="font-['Manrope'] font-extrabold text-lg
                                {{ $av->media >= 4 ? 'text-[#006947]' : ($av->media >= 3 ? 'text-[#0058be]' : 'text-amber-600') }}">
                                {{ number_format($av->media, 1) }}
                            </span>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-5 py-10 text-center">
                <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">history</span>
                <p class="text-sm text-[#727785] mt-2">Nenhum resultado disponível ainda.</p>
            </div>
            @endif
        </div>

    </div>
</div>
</div>
