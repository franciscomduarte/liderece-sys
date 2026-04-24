@section('page-title', 'Avaliações')
@section('page-subtitle', 'Monitoramento e gestão de todas as avaliações do sistema')

<div>
<div class="space-y-6">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
            <div class="w-9 h-9 rounded-xl bg-[#f0f4f8] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#424754] text-lg">assignment</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['total'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Total de avaliações</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="w-9 h-9 rounded-xl bg-[#00855b]/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#006947] text-lg">check_circle</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['enviadas'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Enviadas</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-amber-600 text-lg">pending</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['rascunho'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Em rascunho</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:150ms">
            <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#004395] text-lg">person</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['auto'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Autoavaliações enviadas</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:200ms">
            <div class="w-9 h-9 rounded-xl bg-[#dee2f7] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#414657] text-lg">supervisor_account</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['area'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Avaliações da área enviadas</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] px-5 py-4" style="animation-delay:250ms">
        <div class="flex flex-wrap gap-3">

            {{-- Busca --}}
            <div class="relative grow basis-52 min-w-[180px]">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-lg pointer-events-none">search</span>
                <input type="text" wire:model.live.debounce.300ms="filtroBusca"
                    placeholder="Buscar servidor..."
                    class="w-full pl-9 pr-4 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-[#f6fafe]">
            </div>

            {{-- Ciclo --}}
            <select wire:model.live="filtroCiclo"
                class="grow basis-36 min-w-[130px] px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-[#f6fafe]">
                <option value="">Todos os ciclos</option>
                @foreach($ciclos as $ciclo)
                <option value="{{ $ciclo->id }}">{{ $ciclo->nome }}</option>
                @endforeach
            </select>

            {{-- Área --}}
            <select wire:model.live="filtroArea"
                class="grow basis-36 min-w-[130px] px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-[#f6fafe]">
                <option value="">Todas as áreas</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->nome }}</option>
                @endforeach
            </select>

            {{-- Tipo --}}
            <select wire:model.live="filtroTipo"
                class="grow basis-32 min-w-[110px] px-3 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-[#f6fafe]">
                <option value="">Todos tipos</option>
                <option value="autoavaliacao">Autoavaliação</option>
                <option value="area">Área</option>
            </select>

            {{-- Status --}}
            <select wire:model.live="filtroStatus"
                class="grow basis-32 min-w-[110px] px-3 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-[#f6fafe]">
                <option value="">Todos status</option>
                <option value="rascunho">Rascunho</option>
                <option value="enviada">Enviada</option>
            </select>

        </div>
    </div>

    {{-- Tabela --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:300ms">

        @if($avaliacoes->isEmpty())
        <div class="px-5 py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">search_off</span>
            <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma avaliação encontrada</p>
            <p class="text-sm text-[#727785]">Ajuste os filtros para ver os resultados.</p>
        </div>
        @else

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#f0f4f8]">
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Servidor</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Competência</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Ciclo</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Tipo</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Status</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Nota</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eaeef2]">
                    @foreach($avaliacoes as $avaliacao)
                    <tr class="hover:bg-[#f6fafe] transition-colors">

                        {{-- Servidor --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#d8e2ff] flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-[#004395]">{{ substr($avaliacao->servidor->nome, 0, 1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $avaliacao->servidor->nome }}</p>
                                    <p class="text-xs text-[#727785] truncate">{{ $avaliacao->servidor->area?->nome ?? '—' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Competência --}}
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-[#171c1f] font-medium truncate max-w-[180px]">{{ $avaliacao->competencia->nome }}</p>
                        </td>

                        {{-- Ciclo --}}
                        <td class="px-4 py-3.5">
                            <p class="text-xs text-[#424754] font-semibold whitespace-nowrap">{{ $avaliacao->ciclo->nome }}</p>
                        </td>

                        {{-- Tipo --}}
                        <td class="px-4 py-3.5 text-center">
                            @if($avaliacao->tipo === 'autoavaliacao')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#d8e2ff] text-[#004395]">
                                <span class="material-symbols-outlined text-xs">person</span>
                                Auto
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#dee2f7] text-[#414657]">
                                <span class="material-symbols-outlined text-xs">supervisor_account</span>
                                Área
                            </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3.5 text-center">
                            @if($avaliacao->status === 'enviada')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold bg-[#00855b]/10 text-[#006947]">
                                <span class="material-symbols-outlined text-xs">check_circle</span>
                                Enviada
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                <span class="material-symbols-outlined text-xs">edit</span>
                                Rascunho
                            </span>
                            @endif
                        </td>

                        {{-- Nota --}}
                        <td class="px-4 py-3.5 text-center">
                            @if($avaliacao->media !== null)
                            <span class="font-['Manrope'] font-extrabold text-base
                                {{ $avaliacao->media >= 4 ? 'text-[#006947]' : ($avaliacao->media >= 3 ? 'text-[#0058be]' : 'text-amber-600') }}">
                                {{ number_format($avaliacao->media, 1) }}
                            </span>
                            @else
                            <span class="text-xs text-[#c2c6d6]">—</span>
                            @endif
                        </td>

                        {{-- Ações --}}
                        <td class="px-5 py-3.5 text-right">
                            @if($avaliacao->status === 'enviada')
                            <button wire:click="confirmarReabrir('{{ $avaliacao->id }}')"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border border-[#ba1a1a]/30 text-[#ba1a1a] hover:bg-[#ffdad6] transition-colors">
                                <span class="material-symbols-outlined text-sm">lock_open</span>
                                Reabrir
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

        {{-- Paginação --}}
        @if($avaliacoes->hasPages())
        <div class="px-5 py-4 border-t border-[#eaeef2]">
            {{ $avaliacoes->links() }}
        </div>
        @endif

        @endif
    </div>

</div>

{{-- Modal confirmação reabrir --}}
@if($showConfirmModal)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
     x-data x-init="$el.classList.add('opacity-0'); requestAnimationFrame(() => $el.classList.add('opacity-100'))"
     style="transition: opacity 0.2s">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-[#ffdad6] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[#ba1a1a] text-2xl">lock_open</span>
            </div>
            <div>
                <h3 class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Reabrir avaliação?</h3>
                <p class="text-sm text-[#727785]">A avaliação voltará para rascunho. O avaliador poderá editá-la e reenviar. Esta ação não pode ser desfeita automaticamente.</p>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button wire:click="cancelarReabrir"
                class="flex-1 px-4 py-2.5 rounded-xl border border-[#c2c6d6] text-sm font-semibold text-[#424754] hover:bg-[#f0f4f8] transition-colors">
                Cancelar
            </button>
            <button wire:click="reabrir" wire:loading.attr="disabled"
                class="flex-1 px-4 py-2.5 rounded-xl bg-[#ba1a1a] text-white text-sm font-bold hover:bg-[#93000a] transition-colors disabled:opacity-60">
                <span wire:loading.remove>Reabrir</span>
                <span wire:loading>Reabrindo...</span>
            </button>
        </div>
    </div>
</div>
@endif

</div>
