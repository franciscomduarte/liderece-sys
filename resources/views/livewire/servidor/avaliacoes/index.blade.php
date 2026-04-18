@section('page-title', 'Minhas Avaliações')
@section('page-subtitle', 'Autoavaliação de competências do ciclo atual')

<div>
<div class="space-y-6">

    {{-- Sem ciclo ativo --}}
    @if(! $ciclo)
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">event_busy</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhum ciclo ativo</p>
        <p class="text-sm text-[#727785]">As avaliações ficam disponíveis quando um ciclo é ativado pelo administrador.</p>
    </div>
    @else

    {{-- Banner ciclo ativo --}}
    <div class="flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-[#006947] text-xl">play_circle</span>
        <div class="flex-1">
            <p class="text-[#006947] font-['Manrope'] font-bold text-sm">Ciclo ativo: {{ $ciclo->nome }}</p>
            <p class="text-[#005236] text-xs mt-0.5">
                {{ $ciclo->data_inicio->format('d/m/Y') }} → {{ $ciclo->data_fim->format('d/m/Y') }}
            </p>
        </div>
        @php
            $total = $competencias->count();
            $enviadas = collect($avaliacoes)->filter(fn($a) => $a->status === 'enviada')->count();
        @endphp
        <div class="text-right">
            <p class="font-['Manrope'] font-bold text-[#006947] text-lg">{{ $enviadas }}/{{ $total }}</p>
            <p class="text-[#005236] text-xs">avaliações enviadas</p>
        </div>
    </div>

    {{-- Lista de competências --}}
    @if($competencias->isEmpty())
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">psychology</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma competência vinculada</p>
        <p class="text-sm text-[#727785]">Sua área ainda não possui competências configuradas.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($competencias as $competencia)
        @php
            $avaliacao = $avaliacoes[$competencia->id] ?? null;
            $status = $avaliacao?->status ?? 'nao_iniciada';
        @endphp
        <div
            class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] hover:shadow-[0_20px_60px_rgba(23,28,31,0.10)] hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-4"
            style="animation-delay: {{ $loop->index * 60 }}ms"
        >
            {{-- Ícone tipo --}}
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                @if($competencia->tipo === 'comportamental') bg-[#d8e2ff]
                @elseif($competencia->tipo === 'técnica') bg-[#6ffbbe]/30
                @else bg-[#dee2f7] @endif">
                <span class="material-symbols-outlined text-xl
                    @if($competencia->tipo === 'comportamental') text-[#004395]
                    @elseif($competencia->tipo === 'técnica') text-[#002113]
                    @else text-[#414657] @endif">
                    @if($competencia->tipo === 'gerencial') groups @elseif($competencia->tipo === 'técnica') engineering @else psychology @endif
                </span>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-sm">{{ $competencia->nome }}</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                        @if($competencia->tipo === 'comportamental') bg-[#d8e2ff] text-[#004395]
                        @elseif($competencia->tipo === 'técnica') bg-[#6ffbbe]/40 text-[#002113]
                        @else bg-[#dee2f7] text-[#414657] @endif">
                        {{ ucfirst($competencia->tipo) }}
                    </span>
                    {{-- Status badge --}}
                    @if($status === 'enviada')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#00855b]/10 text-[#006947] border border-[#006947]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#006947]"></span>Enviada
                    </span>
                    @elseif($status === 'rascunho')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Rascunho
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#f0f4f8] text-[#727785]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#c2c6d6]"></span>Não iniciada
                    </span>
                    @endif
                </div>
                <p class="text-xs text-[#727785] mt-0.5">{{ $competencia->itensAtivos->count() }} itens de avaliação</p>
                @if($status === 'enviada' && $avaliacao->media !== null)
                <p class="text-xs text-[#006947] mt-0.5 font-semibold">Média: {{ number_format($avaliacao->media, 1) }}</p>
                @endif
            </div>

            {{-- Ação --}}
            <div class="shrink-0">
                @if($status === 'enviada')
                <a href="{{ route('servidor.avaliacoes.resultado', $avaliacao) }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-[#00855b]/10 text-[#006947] hover:bg-[#00855b]/20 transition-colors">
                    <span class="material-symbols-outlined text-base">visibility</span>
                    Ver resultado
                </a>
                @elseif($status === 'rascunho')
                <a href="{{ route('servidor.avaliacoes.form', $avaliacao) }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                    <span class="material-symbols-outlined text-base">edit</span>
                    Continuar
                </a>
                @else
                <button wire:click="iniciar('{{ $competencia->id }}')"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined text-base">play_arrow</span>
                    Iniciar
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @endif

</div>
</div>
