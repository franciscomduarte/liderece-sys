@section('page-title', 'Contestações')
@section('page-subtitle', 'Visão geral de todas as contestações do sistema')

<div>
<div class="space-y-6">

    {{-- Totais --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
            <p class="text-xs font-bold text-[#727785] uppercase tracking-wide mb-1">Pendentes</p>
            <p class="font-['Manrope'] font-extrabold text-2xl text-amber-600">{{ $totais['pendente'] }}</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:60ms">
            <p class="text-xs font-bold text-[#727785] uppercase tracking-wide mb-1">Respondidas</p>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#004395]">{{ $totais['respondida'] }}</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:120ms">
            <p class="text-xs font-bold text-[#727785] uppercase tracking-wide mb-1">Encerradas</p>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#727785]">{{ $totais['encerrada'] }}</p>
        </div>
    </div>

    {{-- Filtros e busca --}}
    <div class="flex items-center gap-3 flex-wrap">
        <div class="relative flex-1 min-w-48">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-lg">search</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por servidor..."
                class="w-full pl-10 pr-4 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-white">
        </div>
        <div class="flex gap-2">
            @foreach(['' => 'Todas', 'pendente' => 'Pendentes', 'respondida' => 'Respondidas', 'encerrada' => 'Encerradas'] as $val => $label)
            <button wire:click="$set('filtroStatus', '{{ $val }}')"
                class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all
                    {{ $filtroStatus === $val
                        ? 'bg-[#0058be] text-white shadow-md shadow-[#0058be]/20'
                        : 'bg-white text-[#424754] shadow-[0_2px_8px_rgba(23,28,31,0.06)] hover:bg-[#f0f4f8]' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Tabela --}}
    @if($contestacoes->isEmpty())
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">gavel</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma contestação encontrada</p>
        <p class="text-sm text-[#727785]">Ajuste os filtros para ver mais resultados.</p>
    </div>
    @else
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#f0f4f8]">
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Servidor / Área</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Competência</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Data</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Status</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eaeef2]">
                    @foreach($contestacoes as $contestacao)
                    <tr class="hover:bg-[#f6fafe] transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="text-sm font-semibold text-[#171c1f]">{{ $contestacao->servidor->nome }}</p>
                            <p class="text-xs text-[#727785]">{{ $contestacao->servidor->area->nome ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-[#171c1f]">{{ $contestacao->avaliacao->competencia->nome }}</p>
                            <p class="text-xs text-[#727785]">{{ $contestacao->avaliacao->ciclo->nome }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-[#424754]">{{ $contestacao->created_at->format('d/m/Y') }}</p>
                            @if($contestacao->isPendente() && $contestacao->prazo_resposta)
                            <p class="text-xs text-amber-600 font-semibold">Prazo: {{ $contestacao->prazo_resposta->format('d/m/Y') }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold
                                {{ $contestacao->status === 'pendente' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($contestacao->status === 'respondida' ? 'bg-[#d8e2ff] text-[#004395]' : 'bg-[#f0f4f8] text-[#727785]') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $contestacao->status === 'pendente' ? 'bg-amber-500' : ($contestacao->status === 'respondida' ? 'bg-[#004395]' : 'bg-[#c2c6d6]') }}"></span>
                                {{ ucfirst($contestacao->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($contestacao->isPendente())
                            <button wire:click="abrirResposta('{{ $contestacao->id }}')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-sm hover:scale-[1.02] transition-all">
                                <span class="material-symbols-outlined text-sm">reply</span>
                                Responder
                            </button>
                            @else
                            <span class="text-xs text-[#c2c6d6]">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $contestacoes->links() }}
    @endif

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
            <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Resposta <span class="text-[#ba1a1a]">*</span></label>
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
