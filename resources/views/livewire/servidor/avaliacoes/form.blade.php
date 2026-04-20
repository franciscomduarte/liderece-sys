@section('page-title', 'Preencher Avaliação')
@section('page-subtitle', $avaliacao->competencia->nome)

<div>
<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Header da competência --}}
    <div class="float-in bg-white rounded-2xl px-6 py-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                @if($avaliacao->competencia->tipo === 'comportamental') bg-[#d8e2ff]
                @elseif($avaliacao->competencia->tipo === 'técnica') bg-[#6ffbbe]/30
                @else bg-[#dee2f7] @endif">
                <span class="material-symbols-outlined text-2xl
                    @if($avaliacao->competencia->tipo === 'comportamental') text-[#004395]
                    @elseif($avaliacao->competencia->tipo === 'técnica') text-[#002113]
                    @else text-[#414657] @endif">
                    @if($avaliacao->competencia->tipo === 'gerencial') groups @elseif($avaliacao->competencia->tipo === 'técnica') engineering @else psychology @endif
                </span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f] text-lg">{{ $avaliacao->competencia->nome }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                        @if($avaliacao->competencia->tipo === 'comportamental') bg-[#d8e2ff] text-[#004395]
                        @elseif($avaliacao->competencia->tipo === 'técnica') bg-[#6ffbbe]/40 text-[#002113]
                        @else bg-[#dee2f7] text-[#414657] @endif">
                        {{ ucfirst($avaliacao->competencia->tipo) }}
                    </span>
                </div>
                @if($avaliacao->competencia->descricao)
                <p class="text-sm text-[#727785]">{{ $avaliacao->competencia->descricao }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Legenda da escala --}}
    <div class="float-in bg-[#f0f4f8] rounded-xl px-5 py-3" style="animation-delay: 60ms">
        <p class="text-xs font-bold text-[#424754] uppercase tracking-wide mb-2">Escala de avaliação</p>
        <div class="flex gap-3 flex-wrap">
            @foreach([1 => 'Não demonstrado', 2 => 'Abaixo do esperado', 3 => 'Dentro do esperado', 4 => 'Acima do esperado', 5 => 'Referência'] as $n => $label)
            <div class="flex items-center gap-1.5">
                <span class="w-6 h-6 rounded-lg text-xs font-bold flex items-center justify-center
                    @if($n === 1) bg-[#ffdad6] text-[#ba1a1a]
                    @elseif($n === 2) bg-orange-100 text-orange-700
                    @elseif($n === 3) bg-amber-100 text-amber-700
                    @elseif($n === 4) bg-[#6ffbbe]/40 text-[#006947]
                    @else bg-[#00855b]/20 text-[#005236] @endif">{{ $n }}</span>
                <span class="text-xs text-[#424754]">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Itens --}}
    <div class="space-y-3">
        @foreach($avaliacao->competencia->itensAtivos as $item)
        @php $notaAtual = isset($notas[$item->id]) ? (int)$notas[$item->id] : null; @endphp
        <div
            class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]"
            style="animation-delay: {{ ($loop->index + 2) * 60 }}ms"
        >
            <div class="flex items-start gap-4">
                <div class="w-7 h-7 rounded-lg bg-[#f0f4f8] flex items-center justify-center shrink-0 mt-0.5">
                    <span class="text-xs font-bold text-[#727785]">{{ $loop->iteration }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-[#171c1f] font-medium mb-3">{{ $item->descricao }}</p>
                    <div class="flex gap-2">
                        @foreach([1, 2, 3, 4, 5] as $n)
                        <button
                            type="button"
                            wire:click="$set('notas.{{ $item->id }}', '{{ $n }}')"
                            class="w-11 h-11 rounded-xl font-bold text-sm transition-all duration-200 hover:scale-105 active:scale-95
                                @if($notaAtual === $n && $n === 1) bg-[#ba1a1a] text-white shadow-md
                                @elseif($notaAtual === $n && $n === 2) bg-orange-500 text-white shadow-md
                                @elseif($notaAtual === $n && $n === 3) bg-amber-400 text-white shadow-md
                                @elseif($notaAtual === $n && $n === 4) bg-[#006947] text-white shadow-md
                                @elseif($notaAtual === $n && $n === 5) bg-[#004395] text-white shadow-md
                                @else bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed]
                                @endif"
                        >{{ $n }}</button>
                        @endforeach
                    </div>
                </div>
                @if($notaAtual)
                <div class="shrink-0">
                    <span class="material-symbols-outlined text-[#006947] text-xl">check_circle</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Ações --}}
    <div class="float-in bg-white rounded-2xl px-6 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] flex items-center justify-between gap-4" style="animation-delay: 300ms">
        <a href="{{ route('servidor.avaliacoes') }}" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#f0f4f8] transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Voltar
        </a>
        <div class="flex gap-3">
            <button wire:click="salvar" wire:loading.attr="disabled"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-[#0058be] bg-[#d8e2ff] hover:bg-[#c0cefd] transition-colors">
                <span wire:loading wire:target="salvar" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="salvar" class="material-symbols-outlined text-base">save</span>
                Salvar rascunho
            </button>
            <button wire:click="confirmarEnvio"
                class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span class="material-symbols-outlined text-base">send</span>
                Enviar avaliação
            </button>
        </div>
    </div>

</div>

{{-- Modal confirmação de envio --}}
<div x-data x-show="$wire.showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#d8e2ff] flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[#0058be] text-2xl">send</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Enviar avaliação?</h3>
        <p class="text-sm text-[#727785] mb-6">Após o envio, a avaliação não poderá ser alterada. Verifique se todos os itens foram preenchidos.</p>
        <div class="flex gap-3">
            <button wire:click="$set('showConfirmModal', false)" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] border border-[#c2c6d6] hover:bg-[#f0f4f8] transition-colors">Cancelar</button>
            <button wire:click="enviar" wire:loading.attr="disabled" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-[#0058be] to-[#2170e4] shadow-md hover:scale-[1.02] transition-all">
                <span wire:loading wire:target="enviar" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="enviar">Confirmar</span>
            </button>
        </div>
    </div>
</div>

</div>
