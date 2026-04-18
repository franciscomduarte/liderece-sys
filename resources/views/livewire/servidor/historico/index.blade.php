@section('page-title', 'Histórico')
@section('page-subtitle', 'Suas avaliações por ciclo')

<div>
<div class="space-y-6">

@if($historico->isEmpty())
<div class="float-in bg-white rounded-2xl p-12 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
    <span class="material-symbols-outlined text-4xl text-[#c2c6d6]">history</span>
    <h3 class="font-['Manrope'] font-bold text-[#171c1f] mt-3 mb-1">Nenhum histórico disponível</h3>
    <p class="text-[#727785] text-sm">Suas avaliações enviadas aparecerão aqui.</p>
</div>
@else

@foreach($historico as $entry)
<div class="float-in" style="animation-delay: {{ $loop->index * 80 }}ms">

    {{-- Header do ciclo --}}
    <div class="flex items-center gap-3 mb-3 px-1">
        <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[#004395] text-lg">calendar_month</span>
        </div>
        <div class="flex-1">
            <h3 class="font-['Manrope'] font-bold text-[#171c1f]">{{ $entry['ciclo']->nome }}</h3>
            <p class="text-xs text-[#727785]">
                {{ $entry['ciclo']->data_inicio->format('d/m/Y') }} — {{ $entry['ciclo']->data_fim->format('d/m/Y') }}
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold
            {{ $entry['media_ciclo'] >= 4 ? 'bg-[#6ffbbe]/20 text-[#006947]' : ($entry['media_ciclo'] >= 3 ? 'bg-[#d8e2ff] text-[#004395]' : 'bg-amber-50 text-amber-700') }}">
            <span class="material-symbols-outlined text-base">stars</span>
            Média {{ number_format($entry['media_ciclo'], 1) }}
        </span>
    </div>

    {{-- Cards de competências --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($entry['avaliacoes'] as $avaliacao)
        <a href="{{ route('servidor.avaliacoes.resultado', $avaliacao) }}"
           class="bg-white rounded-xl px-4 py-4 shadow-[0_2px_12px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] hover:shadow-[0_6px_20px_rgba(23,28,31,0.10)] hover:ring-[#0058be]/20 transition-all group">
            <div class="flex items-start justify-between gap-2 mb-3">
                <p class="font-semibold text-[#171c1f] text-sm leading-snug group-hover:text-[#0058be] transition-colors">
                    {{ $avaliacao->competencia->nome }}
                </p>
                @php
                    $tipo = $avaliacao->competencia->tipo;
                    $badgeClass = match($tipo) {
                        'comportamental' => 'bg-[#d8e2ff] text-[#004395]',
                        'técnica'        => 'bg-[#6ffbbe]/20 text-[#002113]',
                        default          => 'bg-[#dee2f7] text-[#414657]',
                    };
                @endphp
                <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                    {{ ucfirst($tipo) }}
                </span>
            </div>

            <div class="flex items-end justify-between">
                <div>
                    <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">
                        {{ $avaliacao->media !== null ? number_format($avaliacao->media, 1) : '—' }}
                    </p>
                    <p class="text-[10px] text-[#727785] mt-0.5">
                        {{ $avaliacao->enviada_at->format('d/m/Y') }}
                    </p>
                </div>

                @if($avaliacao->contestacao)
                @php $st = $avaliacao->contestacao->status; @endphp
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold
                    {{ $st === 'respondida' ? 'bg-[#d8e2ff] text-[#004395]' : ($st === 'encerrada' ? 'bg-[#f0f4f8] text-[#727785]' : 'bg-amber-50 text-amber-700') }}">
                    <span class="material-symbols-outlined text-[10px]">gavel</span>
                    {{ ucfirst($st) }}
                </span>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>
@endforeach

@endif
</div>
</div>
