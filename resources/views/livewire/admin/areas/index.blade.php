@section('page-title', 'Áreas')
@section('page-subtitle', 'Gerencie as áreas organizacionais')

<div>
<div class="space-y-6">

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <div class="relative flex-1 max-w-sm">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#727785] text-lg pointer-events-none">search</span>
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Buscar área..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm"
            >
        </div>
        <button
            wire:click="openCreate"
            class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
        >
            <span class="material-symbols-outlined text-lg">add</span>
            Nova Área
        </button>
    </div>

    {{-- Árvore de áreas (sem busca) / tabela plana (com busca) --}}
    @if($search)

    {{-- Resultado de busca: lista plana --}}
    <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] overflow-hidden ring-1 ring-black/[0.04]">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f0f4f8] border-b border-[#eaeef2]">
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754]">Área</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Área pai</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Tipo</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Responsável</th>
                    <th class="text-center px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden sm:table-cell">Servidores</th>
                    <th class="px-6 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#eaeef2]">
                @forelse($areas as $area)
                @include('livewire.admin.areas._row', ['area' => $area, 'indent' => 0])
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">search_off</span>
                        <p class="text-[#727785] text-sm">Nenhuma área encontrada para "{{ $search }}"</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($areas->hasPages())
        <div class="px-6 py-4 border-t border-[#eaeef2]">{{ $areas->links() }}</div>
        @endif
    </div>

    @else

    {{-- Árvore hierárquica --}}
    @if(empty($arvore))
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">domain_disabled</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma área cadastrada</p>
        <p class="text-sm text-[#727785]">Crie a primeira área para organizar os servidores.</p>
    </div>
    @else
    @php
        $allParentIds = collect($arvore)->filter(fn($n) => $n['has_children'])->map(fn($n) => $n['area']->id)->values()->toArray();
    @endphp
    <div
        x-data="{
            expanded: [],
            toggle(id) {
                const idx = this.expanded.indexOf(id);
                idx > -1 ? this.expanded.splice(idx, 1) : this.expanded.push(id);
            },
            isOpen(id) { return this.expanded.includes(id); },
            isVisible(ancestors) { return ancestors.every(id => this.expanded.includes(id)); },
            expandAll() { this.expanded = {{ Js::from($allParentIds) }}; },
            collapseAll() { this.expanded = []; }
        }"
        class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] overflow-hidden ring-1 ring-black/[0.04]"
    >
        {{-- Barra superior com expand/collapse --}}
        <div class="flex items-center justify-end gap-2 px-6 py-3 border-b border-[#eaeef2] bg-[#f6fafe]">
            <button @click="expandAll()"
                class="flex items-center gap-1 text-xs font-semibold text-[#0058be] hover:underline">
                <span class="material-symbols-outlined text-sm">unfold_more</span>
                Expandir tudo
            </button>
            <span class="text-[#c2c6d6]">|</span>
            <button @click="collapseAll()"
                class="flex items-center gap-1 text-xs font-semibold text-[#727785] hover:underline">
                <span class="material-symbols-outlined text-sm">unfold_less</span>
                Recolher tudo
            </button>
        </div>
        <table class="w-full">
            <thead>
                <tr class="bg-[#f0f4f8] border-b border-[#eaeef2]">
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754]">Área</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Área pai</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Tipo</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Responsável</th>
                    <th class="text-center px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden sm:table-cell">Servidores</th>
                    <th class="px-6 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#eaeef2]">
                @foreach($arvore as $node)
                    @include('livewire.admin.areas._row', [
                        'area'         => $node['area'],
                        'depth'        => $node['depth'],
                        'ancestorIds'  => $node['ancestor_ids'],
                        'hasChildren'  => $node['has_children'],
                    ])
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @endif

</div>

{{-- Modal criar/editar --}}
<div
    x-data
    x-show="$wire.showModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    style="display:none"
>
    <div
        x-show="$wire.showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-lg ring-1 ring-black/[0.06]"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">domain</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">
                    {{ $editingId ? 'Editar Área' : 'Nova Área' }}
                </h2>
            </div>
            <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Nome da área <span class="text-[#ba1a1a]">*</span></label>
                <input
                    wire:model="nome"
                    type="text"
                    placeholder="Ex: Diretoria de TI"
                    class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all
                        @error('nome') border-[#ba1a1a] bg-[#ffdad6]/20 @else border-[#c2c6d6] @enderror"
                >
                @error('nome') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Tipo de área</label>
                <select wire:model="tipo"
                    class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-white @error('tipo') border-[#ba1a1a] @enderror">
                    <option value="">Não classificada</option>
                    <option value="meio">Meio</option>
                    <option value="finalistica">Finalística</option>
                </select>
                @error('tipo') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Área pai</label>
                <select wire:model="parent_id"
                    class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-white @error('parent_id') border-[#ba1a1a] @enderror">
                    <option value="">Nenhuma (área raiz)</option>
                    @foreach($areasParaSelect as $ap)
                    <option value="{{ $ap->id }}">{{ $ap->parent ? '↳ ' . $ap->parent->nome . ' › ' . $ap->nome : $ap->nome }}</option>
                    @endforeach
                </select>
                @error('parent_id') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-[#727785] mt-1">Deixe em branco para criar uma área de nível superior.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Responsável</label>
                <input
                    wire:model="responsavel"
                    type="text"
                    placeholder="Nome do responsável pela área"
                    class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Descrição</label>
                <textarea
                    wire:model="descricao"
                    rows="3"
                    placeholder="Descreva brevemente as atribuições desta área..."
                    class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all resize-none"
                ></textarea>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button
                wire:click="$set('showModal', false)"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors"
            >
                Cancelar
            </button>
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-not-allowed"
                class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
                <span wire:loading wire:target="save" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-base">check</span>
                Salvar
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmação de exclusão --}}
<div
    x-data
    x-show="$wire.showDeleteModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    style="display:none"
>
    <div
        x-show="$wire.showDeleteModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center"
    >
        <div class="w-14 h-14 rounded-2xl bg-[#ffdad6] flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[#ba1a1a] text-2xl">delete_forever</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Excluir área?</h3>
        <p class="text-sm text-[#727785] mb-6">Esta ação não pode ser desfeita. Áreas com servidores vinculados não podem ser excluídas.</p>
        <div class="flex gap-3 justify-center">
            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] border border-[#c2c6d6] hover:bg-[#f0f4f8] transition-colors">
                Cancelar
            </button>
            <button wire:click="delete" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors">
                Excluir
            </button>
        </div>
    </div>
</div>
</div>
