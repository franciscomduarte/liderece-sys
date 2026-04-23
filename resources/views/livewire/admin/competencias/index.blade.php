@section('page-title', 'Competências')
@section('page-subtitle', 'Gerencie as competências e seus itens de avaliação')

<div>
<div class="space-y-6">

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-2 flex-1">
            <div class="relative flex-1 max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#727785] text-lg pointer-events-none">search</span>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Buscar competência..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm"
                >
            </div>
            <select wire:model.live="filtroTipo" class="px-3 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm text-[#424754] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm">
                <option value="">Todos os tipos</option>
                <option value="organizacional">Organizacional</option>
                <option value="técnica">Técnica</option>
                <option value="gerencial">Gerencial</option>
            </select>
        </div>
        <button
            wire:click="openCreate"
            class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
        >
            <span class="material-symbols-outlined text-lg">add</span>
            Nova Competência
        </button>
    </div>

    {{-- Cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($competencias as $competencia)
        @php
            $tipoBadge = match($competencia->tipo) {
                'organizacional' => ['bg' => 'bg-[#d8e2ff]', 'text' => 'text-[#004395]', 'icon' => 'psychology'],
                'técnica'        => ['bg' => 'bg-[#6ffbbe]/40', 'text' => 'text-[#002113]', 'icon' => 'engineering'],
                'gerencial'      => ['bg' => 'bg-[#dee2f7]', 'text' => 'text-[#414657]', 'icon' => 'manage_accounts'],
                default          => ['bg' => 'bg-[#f0f4f8]', 'text' => 'text-[#424754]', 'icon' => 'workspace_premium'],
            };
        @endphp
        <div
            class="float-in bg-white rounded-2xl p-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] hover:shadow-[0_20px_60px_rgba(23,28,31,0.10)] hover:-translate-y-0.5 transition-all duration-300 group flex flex-col"
            style="animation-delay: {{ $loop->index * 50 }}ms"
        >
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl {{ $tipoBadge['bg'] }} flex items-center justify-center">
                        <span class="material-symbols-outlined {{ $tipoBadge['text'] }} text-lg">{{ $tipoBadge['icon'] }}</span>
                    </div>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-bold {{ $tipoBadge['bg'] }} {{ $tipoBadge['text'] }} capitalize">
                        {{ $competencia->tipo }}
                    </span>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button wire:click="openEdit('{{ $competencia->id }}')" class="w-8 h-8 rounded-lg hover:bg-[#d8e2ff] flex items-center justify-center transition-colors" title="Editar">
                        <span class="material-symbols-outlined text-[#0058be] text-base">edit</span>
                    </button>
                    <button wire:click="confirmDelete('{{ $competencia->id }}')" class="w-8 h-8 rounded-lg hover:bg-[#ffdad6] flex items-center justify-center transition-colors" title="Excluir">
                        <span class="material-symbols-outlined text-[#ba1a1a] text-base">delete</span>
                    </button>
                </div>
            </div>

            <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-sm mb-1">{{ $competencia->nome }}</h3>
            @if($competencia->descricao)
            <p class="text-xs text-[#727785] line-clamp-2 mb-3">{{ $competencia->descricao }}</p>
            @endif

            <div class="mt-auto pt-3 border-t border-[#eaeef2] flex items-center justify-between">
                <span class="flex items-center gap-1 text-xs text-[#727785]">
                    <span class="material-symbols-outlined text-sm">list_alt</span>
                    {{ $competencia->itens_count }} {{ $competencia->itens_count === 1 ? 'item' : 'itens' }}
                </span>
                @if(!$competencia->ativo)
                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#f0f4f8] text-[#727785]">Inativa</span>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
            <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">workspace_premium</span>
            <p class="text-[#727785] text-sm">Nenhuma competência cadastrada ainda.</p>
        </div>
        @endforelse
    </div>

    @if($competencias->hasPages())
    <div class="bg-white rounded-2xl px-6 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)]">
        {{ $competencias->links() }}
    </div>
    @endif

</div>

{{-- Modal criar/editar --}}
<div x-data x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-2xl ring-1 ring-black/[0.06] overflow-y-auto max-h-[92vh]">

        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2] sticky top-0 bg-white z-10 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">workspace_premium</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">{{ $editingId ? 'Editar Competência' : 'Nova Competência' }}</h2>
            </div>
            <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>

        <div class="px-6 py-5 space-y-5">
            {{-- Dados básicos --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Nome <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="nome" type="text" placeholder="Nome da competência"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('nome') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('nome') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Tipo <span class="text-[#ba1a1a]">*</span></label>
                        <select wire:model="tipo" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all">
                            <option value="organizacional">Organizacional</option>
                            <option value="técnica">Técnica</option>
                            <option value="gerencial">Gerencial</option>
                        </select>
                    </div>
                    <div class="flex items-end pb-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative">
                                <input wire:model="ativo" type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-[#c2c6d6] rounded-full peer-checked:bg-[#0058be] transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-semibold text-[#424754]">Ativa</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Descrição</label>
                    <textarea wire:model="descricao" rows="2" placeholder="Descreva brevemente esta competência..."
                        class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all resize-none"></textarea>
                </div>
            </div>

            {{-- Itens de avaliação --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-bold text-[#424754] uppercase tracking-wide">Itens de avaliação <span class="text-[#ba1a1a]">*</span></label>
                    <button wire:click="addItem" type="button" class="flex items-center gap-1 text-xs font-bold text-[#0058be] hover:text-[#004395] transition-colors">
                        <span class="material-symbols-outlined text-sm">add_circle</span>
                        Adicionar item
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($itens as $index => $item)
                    <div class="flex items-center gap-2 float-in" style="animation-delay: {{ $index * 30 }}ms">
                        <span class="w-6 h-6 rounded-full bg-[#f0f4f8] flex items-center justify-center text-xs font-bold text-[#727785] shrink-0">{{ $index + 1 }}</span>
                        <input
                            wire:model="itens.{{ $index }}.descricao"
                            type="text"
                            placeholder="Descreva o critério de avaliação..."
                            class="flex-1 px-3 py-2 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all"
                        >
                        @if(count($itens) > 1)
                        <button wire:click="removeItem({{ $index }})" type="button" class="w-8 h-8 rounded-lg hover:bg-[#ffdad6] flex items-center justify-center transition-colors shrink-0">
                            <span class="material-symbols-outlined text-[#ba1a1a] text-base">remove_circle</span>
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Áreas vinculadas com nível esperado --}}
            @if(count($areas) > 0)
            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1">Áreas vinculadas</label>
                <p class="text-xs text-[#727785] mb-2">Defina o nível de proficiência esperado para cada área.</p>
                <label class="flex items-center gap-2 cursor-pointer mb-3 px-3 py-2 rounded-xl bg-[#f0f4f8] hover:bg-[#eaeef2] transition-colors w-fit">
                    <input wire:model="selecionarTodasAreas" wire:change="toggleTodasAreas" type="checkbox" class="w-4 h-4 rounded accent-[#0058be]">
                    <span class="text-xs font-semibold text-[#424754]">Selecionar todas as áreas</span>
                </label>
                <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                    @foreach($areas as $area)
                    @php $selecionada = in_array($area->id, $areaIds); @endphp
                    <div class="flex items-center gap-3 rounded-xl border px-3 py-2 transition-colors
                        {{ $selecionada ? 'bg-[#d8e2ff]/30 border-[#0058be]/30' : 'border-[#eaeef2] hover:bg-[#f0f4f8]' }}">
                        <label class="flex items-center gap-2 cursor-pointer flex-1 min-w-0">
                            <input wire:model="areaIds" type="checkbox" value="{{ $area->id }}" class="w-4 h-4 shrink-0 rounded accent-[#0058be]">
                            <span class="text-sm text-[#424754] truncate">{{ $area->nome }}</span>
                        </label>
                        @if($selecionada)
                        <select wire:model="niveisEsperados.{{ $area->id }}"
                            class="shrink-0 text-xs px-2 py-1.5 border border-[#c2c6d6] rounded-lg bg-white text-[#424754] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all">
                            <option value="1">Nível 1 — Inicial</option>
                            <option value="2">Nível 2 — Básico</option>
                            <option value="3">Nível 3 — Proficiente</option>
                            <option value="4">Nível 4 — Avançado</option>
                            <option value="5">Nível 5 — Referência</option>
                        </select>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors">Cancelar</button>
            <button wire:click="save" wire:loading.attr="disabled" class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-base">check</span>
                Salvar
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmar exclusão --}}
<div x-data x-show="$wire.showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#ffdad6] flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[#ba1a1a] text-2xl">delete_forever</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Excluir competência?</h3>
        <p class="text-sm text-[#727785] mb-6">Todos os itens de avaliação vinculados serão removidos. Competências com avaliações não podem ser excluídas.</p>
        <div class="flex gap-3">
            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] border border-[#c2c6d6] hover:bg-[#f0f4f8] transition-colors">Cancelar</button>
            <button wire:click="delete" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors">Excluir</button>
        </div>
    </div>
</div>
</div>
