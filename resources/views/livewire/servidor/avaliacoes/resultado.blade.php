@section('page-title', 'Resultado da Avaliação')
@section('page-subtitle', $avaliacao->competencia->nome)

<div>
<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Card de resultado --}}
    <div class="float-in bg-white rounded-2xl px-6 py-6 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-2xl bg-[#00855b]/10 flex items-center justify-center shrink-0">
                <span class="font-['Manrope'] font-extrabold text-2xl text-[#006947]">
                    {{ $avaliacao->media !== null ? number_format($avaliacao->media, 1) : '—' }}
                </span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f] text-lg">{{ $avaliacao->competencia->nome }}</h2>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#00855b]/10 text-[#006947] border border-[#006947]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#006947]"></span>Enviada
                    </span>
                </div>
                <p class="text-xs text-[#727785]">
                    Enviada em {{ $avaliacao->enviada_at->format('d/m/Y \à\s H:i') }}
                    · {{ $avaliacao->respostas->count() }} itens avaliados
                </p>
            </div>
        </div>

        @if($avaliacao->media !== null)
        <div class="mt-5">
            <div class="flex justify-between text-xs text-[#727785] mb-1.5">
                <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
            </div>
            <div class="h-2.5 bg-[#f0f4f8] rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-[#0058be] to-[#2170e4]"
                    style="width: {{ ($avaliacao->media / 5) * 100 }}%"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- Respostas por item --}}
    @php
        $respostasIndexadas = $avaliacao->respostas->keyBy('item_id');
        $labels = [1 => 'Não demonstrado', 2 => 'Abaixo do esperado', 3 => 'Dentro do esperado', 4 => 'Acima do esperado', 5 => 'Referência'];
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

    {{-- Status da contestação --}}
    @if($avaliacao->contestacao)
        @php $c = $avaliacao->contestacao; @endphp
        <div class="float-in rounded-xl px-5 py-4 flex items-start gap-3 border
            {{ $c->status === 'respondida' ? 'bg-[#d8e2ff] border-[#004395]/20' : ($c->status === 'encerrada' ? 'bg-[#f0f4f8] border-[#c2c6d6]' : 'bg-amber-50 border-amber-200') }}"
            style="animation-delay: 300ms">
            <span class="material-symbols-outlined text-xl shrink-0 mt-0.5
                {{ $c->status === 'respondida' ? 'text-[#004395]' : ($c->status === 'encerrada' ? 'text-[#727785]' : 'text-amber-600') }}">gavel</span>
            <div class="flex-1">
                <p class="text-sm font-semibold
                    {{ $c->status === 'respondida' ? 'text-[#001a42]' : ($c->status === 'encerrada' ? 'text-[#424754]' : 'text-amber-700') }}">
                    Contestação
                    @if($c->status === 'respondida') respondida
                    @elseif($c->status === 'encerrada') encerrada
                    @else pendente @endif
                </p>
                <p class="text-xs mt-1 text-[#424754]">{{ $c->justificativa }}</p>
                @if($c->resposta_gestor)
                <div class="mt-2 pt-2 border-t border-[#004395]/10">
                    <p class="text-xs font-semibold text-[#004395]">Resposta do gestor:</p>
                    <p class="text-xs text-[#424754] mt-0.5">{{ $c->resposta_gestor }}</p>
                </div>
                @endif
            </div>
        </div>

    @elseif($podeContestar)
        <div class="float-in bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-center justify-between gap-4" style="animation-delay: 300ms">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-amber-600 text-xl shrink-0 mt-0.5">info</span>
                <div>
                    <p class="text-sm font-semibold text-amber-700">Contestação disponível</p>
                    @php $prazo = $avaliacao->enviada_at->copy()->addDays($avaliacao->ciclo->prazo_contestacao_dias); @endphp
                    <p class="text-xs text-amber-600 mt-0.5">Prazo até {{ $prazo->format('d/m/Y') }}</p>
                </div>
            </div>
            <button wire:click="$set('showContestacaoModal', true)"
                class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-amber-500 text-white hover:bg-amber-600 transition-colors">
                <span class="material-symbols-outlined text-base">gavel</span>
                Contestar
            </button>
        </div>

    @else
        <div class="float-in bg-[#f0f4f8] border border-[#c2c6d6] rounded-xl px-5 py-4 flex items-start gap-3" style="animation-delay: 300ms">
            <span class="material-symbols-outlined text-[#c2c6d6] text-xl shrink-0 mt-0.5">lock</span>
            <p class="text-sm text-[#727785]">Prazo de contestação encerrado.</p>
        </div>
    @endif

    <div class="float-in" style="animation-delay: 360ms">
        <a href="{{ route('servidor.avaliacoes') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#f0f4f8] transition-colors">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Voltar para avaliações
        </a>
    </div>

</div>

{{-- Modal contestação --}}
<div x-data x-show="$wire.showContestacaoModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showContestacaoModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-lg ring-1 ring-black/[0.06]">

        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-600 text-lg">gavel</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Contestar Avaliação</h2>
            </div>
            <button wire:click="$set('showContestacaoModal', false)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-3">
                <span class="material-symbols-outlined text-amber-600 text-lg shrink-0 mt-0.5">info</span>
                <p class="text-xs text-amber-700">Descreva claramente o motivo da contestação. Você só pode contestar <strong>uma vez</strong> por avaliação.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Justificativa <span class="text-[#ba1a1a]">*</span></label>
                <textarea wire:model="justificativa" rows="5"
                    placeholder="Descreva o motivo da sua contestação (mínimo 20 caracteres)..."
                    class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] resize-none transition-all @error('justificativa') border-[#ba1a1a] @else border-[#c2c6d6] @enderror"></textarea>
                @error('justificativa') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button wire:click="$set('showContestacaoModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors">Cancelar</button>
            <button wire:click="contestar" wire:loading.attr="disabled" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span wire:loading wire:target="contestar" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="contestar" class="material-symbols-outlined text-base">send</span>
                Enviar contestação
            </button>
        </div>
    </div>
</div>

</div>
