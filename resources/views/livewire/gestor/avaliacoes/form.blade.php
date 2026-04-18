@section('page-title', 'Avaliar Servidor')
@section('page-subtitle', $avaliacao->servidor->nome . ' — ' . $avaliacao->competencia->nome)

<div>
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="float-in bg-white rounded-2xl px-6 py-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#d8e2ff] flex items-center justify-center shrink-0">
                <span class="font-bold text-xl text-[#004395]">{{ substr($avaliacao->servidor->nome, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">{{ $avaliacao->servidor->nome }}</h2>
                <p class="text-xs text-[#727785]">{{ $avaliacao->servidor->cargo }} · {{ $avaliacao->competencia->nome }}</p>
            </div>
            @if($avaliacao->isEnviada())
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#00855b]/10 text-[#006947] border border-[#006947]/20">
                <span class="w-1.5 h-1.5 rounded-full bg-[#006947]"></span>Enviada
            </span>
            @endif
        </div>
    </div>

    {{-- Legenda --}}
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

    {{-- Itens: autoavaliação vs gestor --}}
    @php
        $autoRespostas = $autoavaliacao?->respostas->keyBy('item_id') ?? collect();
        $labels = [1 => 'Não demonstrado', 2 => 'Abaixo do esperado', 3 => 'Dentro do esperado', 4 => 'Acima do esperado', 5 => 'Referência'];
    @endphp
    <div class="space-y-3">
        @foreach($avaliacao->competencia->itensAtivos as $item)
        @php
            $notaGestor = isset($notas[$item->id]) ? (int)$notas[$item->id] : null;
            $notaAuto = $autoRespostas[$item->id]?->nota ?? null;
        @endphp
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]"
            style="animation-delay: {{ ($loop->index + 2) * 60 }}ms">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-6 h-6 rounded-lg bg-[#f0f4f8] flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-[#727785]">{{ $loop->iteration }}</span>
                </div>
                <p class="text-sm text-[#171c1f] font-medium">{{ $item->descricao }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Autoavaliação (leitura) --}}
                <div>
                    <p class="text-xs font-bold text-[#727785] uppercase tracking-wide mb-2">Autoavaliação do servidor</p>
                    @if($notaAuto)
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl font-bold text-sm flex items-center justify-center
                            {{ $notaAuto === 1 ? 'bg-[#ffdad6] text-[#ba1a1a]' : ($notaAuto === 2 ? 'bg-orange-100 text-orange-700' : ($notaAuto === 3 ? 'bg-amber-100 text-amber-700' : ($notaAuto === 4 ? 'bg-[#6ffbbe]/40 text-[#006947]' : 'bg-[#00855b]/20 text-[#005236]'))) }}">{{ $notaAuto }}</span>
                        <span class="text-xs text-[#727785]">{{ $labels[$notaAuto] }}</span>
                    </div>
                    @else
                    <p class="text-xs text-[#c2c6d6] italic">Não enviada ainda</p>
                    @endif
                </div>

                {{-- Nota do gestor --}}
                <div>
                    <p class="text-xs font-bold text-[#424754] uppercase tracking-wide mb-2">Sua avaliação</p>
                    @if($avaliacao->isEnviada())
                    @if($notaGestor)
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-xl font-bold text-sm flex items-center justify-center
                            {{ $notaGestor === 1 ? 'bg-[#ffdad6] text-[#ba1a1a]' : ($notaGestor === 2 ? 'bg-orange-100 text-orange-700' : ($notaGestor === 3 ? 'bg-amber-100 text-amber-700' : ($notaGestor === 4 ? 'bg-[#6ffbbe]/40 text-[#006947]' : 'bg-[#00855b]/20 text-[#005236]'))) }}">{{ $notaGestor }}</span>
                        <span class="text-xs text-[#727785]">{{ $labels[$notaGestor] }}</span>
                    </div>
                    @endif
                    @else
                    <div class="flex gap-2">
                        @foreach([1, 2, 3, 4, 5] as $n)
                        <button
                            type="button"
                            wire:click="$set('notas.{{ $item->id }}', '{{ $n }}')"
                            class="w-10 h-10 rounded-xl font-bold text-sm transition-all duration-200 hover:scale-105 active:scale-95
                                {{ $notaGestor === $n
                                    ? ($n === 1 ? 'bg-[#ba1a1a] text-white shadow-md' : ($n === 2 ? 'bg-orange-500 text-white shadow-md' : ($n === 3 ? 'bg-amber-400 text-white shadow-md' : ($n === 4 ? 'bg-[#006947] text-white shadow-md' : 'bg-[#004395] text-white shadow-md'))))
                                    : 'bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed]' }}"
                        >{{ $n }}</button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Comentário do gestor --}}
    @if(! $avaliacao->isEnviada())
    <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay: 300ms">
        <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-2">
            Comentário geral <span class="text-[#727785] font-normal normal-case">(opcional)</span>
        </label>
        <textarea wire:model="comentario" rows="3"
            placeholder="Adicione um comentário sobre o desempenho do servidor nesta competência..."
            class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] resize-none transition-all"></textarea>
    </div>
    @elseif($avaliacao->comentario_gestor)
    <div class="float-in bg-[#f6fafe] rounded-xl px-5 py-4 border border-[#c2c6d6]" style="animation-delay: 300ms">
        <p class="text-xs font-bold text-[#727785] uppercase tracking-wide mb-1">Comentário do gestor</p>
        <p class="text-sm text-[#424754]">{{ $avaliacao->comentario_gestor }}</p>
    </div>
    @endif

    {{-- Ações --}}
    <div class="float-in bg-white rounded-2xl px-6 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] flex items-center justify-between gap-4" style="animation-delay: 360ms">
        <a href="{{ route('gestor.avaliacoes') }}" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#f0f4f8] transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Voltar
        </a>
        @if(! $avaliacao->isEnviada())
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
        @endif
    </div>

</div>

{{-- Modal confirmação --}}
<div x-data x-show="$wire.showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showConfirmModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#d8e2ff] flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[#0058be] text-2xl">rate_review</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Enviar avaliação?</h3>
        <p class="text-sm text-[#727785] mb-6">Após o envio, a avaliação não poderá ser alterada.</p>
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
