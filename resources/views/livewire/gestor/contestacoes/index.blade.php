@section('page-title', 'Contestações')
@section('page-subtitle', 'Responda as contestações dos servidores da sua área')

<div>
<div class="space-y-6">

    {{-- Filtros --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['pendente' => 'Pendentes', 'respondida' => 'Respondidas', 'encerrada' => 'Encerradas', '' => 'Todas'] as $val => $label)
        <button wire:click="$set('filtroStatus', '{{ $val }}')"
            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                {{ $filtroStatus === $val
                    ? 'bg-[#0058be] text-white shadow-md shadow-[#0058be]/20'
                    : 'bg-white text-[#424754] shadow-[0_2px_8px_rgba(23,28,31,0.06)] hover:bg-[#f0f4f8]' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Lista --}}
    @forelse($contestacoes as $contestacao)
    <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] hover:-translate-y-0.5 hover:shadow-[0_20px_60px_rgba(23,28,31,0.10)] transition-all duration-300"
        style="animation-delay: {{ $loop->index * 50 }}ms">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                {{ $contestacao->status === 'pendente' ? 'bg-amber-50' : ($contestacao->status === 'respondida' ? 'bg-[#d8e2ff]' : 'bg-[#f0f4f8]') }}">
                <span class="material-symbols-outlined text-lg
                    {{ $contestacao->status === 'pendente' ? 'text-amber-600' : ($contestacao->status === 'respondida' ? 'text-[#004395]' : 'text-[#727785]') }}">gavel</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <p class="font-['Manrope'] font-bold text-[#171c1f] text-sm">{{ $contestacao->servidor->nome }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                        {{ $contestacao->status === 'pendente' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($contestacao->status === 'respondida' ? 'bg-[#d8e2ff] text-[#004395]' : 'bg-[#f0f4f8] text-[#727785]') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $contestacao->status === 'pendente' ? 'bg-amber-500' : ($contestacao->status === 'respondida' ? 'bg-[#004395]' : 'bg-[#c2c6d6]') }}"></span>
                        {{ ucfirst($contestacao->status) }}
                    </span>
                </div>
                <p class="text-xs text-[#727785] mb-2">
                    {{ $contestacao->avaliacao->competencia->nome }}
                    · {{ $contestacao->avaliacao->ciclo->nome }}
                    · {{ $contestacao->created_at->format('d/m/Y') }}
                    @if($contestacao->isPendente() && $contestacao->prazo_resposta)
                    · <span class="text-amber-600 font-semibold">Prazo: {{ $contestacao->prazo_resposta->format('d/m/Y') }}</span>
                    @endif
                </p>
                <p class="text-sm text-[#424754]">{{ Str::limit($contestacao->justificativa, 150) }}</p>
                @if($contestacao->resposta_gestor)
                <div class="mt-2 pt-2 border-t border-[#eaeef2]">
                    <p class="text-xs font-semibold text-[#004395] mb-0.5">Sua resposta:</p>
                    <p class="text-xs text-[#424754]">{{ Str::limit($contestacao->resposta_gestor, 120) }}</p>
                </div>
                @endif
            </div>
            @if($contestacao->isPendente())
            <div class="shrink-0">
                <button wire:click="abrirResposta('{{ $contestacao->id }}')"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined text-base">reply</span>
                    Responder
                </button>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">gavel</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma contestação</p>
        <p class="text-sm text-[#727785]">Nenhuma contestação encontrada para o filtro selecionado.</p>
    </div>
    @endforelse

    {{ $contestacoes->links() }}

</div>

{{-- Modal responder --}}
<div x-data x-show="$wire.respondendoId !== null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.respondendoId !== null" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-lg ring-1 ring-black/[0.06]">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">reply</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Responder Contestação</h2>
            </div>
            <button wire:click="$set('respondendoId', null)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>
        <div class="px-6 py-5">
            <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Sua resposta <span class="text-[#ba1a1a]">*</span></label>
            <textarea wire:model="resposta" rows="4"
                placeholder="Explique sua posição sobre a contestação (mínimo 10 caracteres)..."
                class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] resize-none transition-all @error('resposta') border-[#ba1a1a] @else border-[#c2c6d6] @enderror"></textarea>
            @error('resposta') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button wire:click="$set('respondendoId', null)" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors">Cancelar</button>
            <button wire:click="responder" wire:loading.attr="disabled" class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span wire:loading wire:target="responder" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="responder" class="material-symbols-outlined text-base">send</span>
                Enviar resposta
            </button>
        </div>
    </div>
</div>

</div>
