@section('page-title', 'Resultado da Avaliação')
@section('page-subtitle', $avaliacao->competencia->nome)

<div>
<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Card de resultado --}}
    <div class="float-in bg-white rounded-2xl px-6 py-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                @if($avaliacao->competencia->tipo === 'organizacional') bg-[#d8e2ff]
                @elseif($avaliacao->competencia->tipo === 'técnica') bg-[#6ffbbe]/30
                @else bg-[#dee2f7] @endif">
                <span class="material-symbols-outlined text-2xl
                    @if($avaliacao->competencia->tipo === 'organizacional') text-[#004395]
                    @elseif($avaliacao->competencia->tipo === 'técnica') text-[#002113]
                    @else text-[#414657] @endif">
                    @if($avaliacao->competencia->tipo === 'gerencial') groups
                    @elseif($avaliacao->competencia->tipo === 'técnica') engineering
                    @else psychology @endif
                </span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f] text-lg">{{ $avaliacao->competencia->nome }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                        @if($avaliacao->competencia->tipo === 'organizacional') bg-[#d8e2ff] text-[#004395]
                        @elseif($avaliacao->competencia->tipo === 'técnica') bg-[#6ffbbe]/40 text-[#002113]
                        @else bg-[#dee2f7] text-[#414657] @endif">
                        {{ ucfirst($avaliacao->competencia->tipo) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#00855b]/10 text-[#006947] border border-[#006947]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#006947]"></span>Enviada
                    </span>
                </div>
                @if($avaliacao->competencia->descricao)
                <p class="text-sm text-[#727785]">{{ $avaliacao->competencia->descricao }}</p>
                @endif
                <p class="text-xs text-[#727785] mt-1">
                    Enviada em {{ $avaliacao->enviada_at->format('d/m/Y \à\s H:i') }}
                    · {{ $avaliacao->respostas->count() }} itens avaliados
                </p>
            </div>
            @if($avaliacao->media !== null)
            <div class="shrink-0 w-14 h-14 rounded-2xl bg-[#00855b]/10 flex items-center justify-center">
                <span class="font-['Manrope'] font-extrabold text-2xl text-[#006947]">
                    {{ number_format($avaliacao->media, 1) }}
                </span>
            </div>
            @endif
        </div>

        @if($avaliacao->media !== null)
        <div class="mt-5">
            <div class="flex justify-between text-xs text-[#727785] mb-1.5">
                <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
            </div>
            <div class="h-4 bg-[#eaeef2] rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#0058be] to-[#2170e4]"
                    style="width: {{ (($avaliacao->media - 1) / 4) * 100 }}%"></div>
            </div>
            <div class="flex items-center justify-between mt-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#727785]">Nível de proficiência</span>
                    @php $nivel = $avaliacao->nivel_proficiencia; @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold
                        @if($nivel <= 1) bg-[#ffdad6] text-[#ba1a1a]
                        @elseif($nivel === 2) bg-orange-100 text-orange-700
                        @elseif($nivel === 3) bg-amber-100 text-amber-700
                        @elseif($nivel === 4) bg-[#6ffbbe]/40 text-[#006947]
                        @else bg-[#00855b]/20 text-[#005236] @endif">
                        <span class="material-symbols-outlined text-sm">
                            @if($nivel <= 2) trending_down @elseif($nivel === 3) trending_flat @else trending_up @endif
                        </span>
                        Nível {{ $nivel }}
                    </span>
                </div>
                <span class="text-xs text-[#727785]">
                    Média <strong class="text-[#171c1f]">{{ number_format($avaliacao->media, 1) }}</strong>
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- Respostas por item --}}
    @php
        $respostasIndexadas = $avaliacao->respostas->keyBy('item_id');
        $labels = \App\Services\AvaliacaoService::labelsEscala();
    @endphp
    <div class="space-y-3">
        @foreach($avaliacao->competencia->itensAtivos as $item)
        @php $nota = $respostasIndexadas[$item->id]?->nota ?? null; @endphp
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] flex items-center gap-4"
            style="animation-delay: {{ ($loop->index + 1) * 50 }}ms">
            <div class="w-7 h-7 rounded-lg bg-[#f0f4f8] flex items-center justify-center shrink-0">
                <span class="text-xs font-bold text-[#727785]">{{ $loop->iteration }}</span>
            </div>
            <p class="flex-1 text-sm text-[#171c1f]">{{ $item->descricao }}</p>
            @if($nota)
            <div class="shrink-0 flex items-center gap-2">
                <span class="w-10 h-10 rounded-xl font-bold text-sm flex items-center justify-center
                    {{ $nota === 1 ? 'bg-[#ffdad6] text-[#ba1a1a]' : ($nota === 2 ? 'bg-orange-100 text-orange-700' : ($nota === 3 ? 'bg-amber-100 text-amber-700' : ($nota === 4 ? 'bg-[#6ffbbe]/40 text-[#006947]' : 'bg-[#00855b]/20 text-[#005236]'))) }}">{{ $nota }}</span>
                <span class="text-xs text-[#727785] hidden sm:block">{{ $labels[$nota] }}</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="float-in" style="animation-delay: 360ms">
        <a href="{{ route('servidor.avaliacoes') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#f0f4f8] transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Voltar para avaliações
        </a>
    </div>

</div>
</div>
