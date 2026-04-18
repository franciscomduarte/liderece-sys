@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerencie as configurações do sistema')

<div>
<div class="space-y-6">

    @include('layouts.components.config-subnav')


    {{-- Banner ciclo ativo --}}
    @php $cicloAtivo = $ciclos->firstWhere('status', 'ativo'); @endphp
    @if($cicloAtivo)
    <div class="flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-5 py-4">
        <span class="material-symbols-outlined text-[#006947] text-xl">play_circle</span>
        <div class="flex-1">
            <p class="text-[#006947] font-['Manrope'] font-bold text-sm">Ciclo ativo: {{ $cicloAtivo->nome }}</p>
            <p class="text-[#005236] text-xs mt-0.5">
                {{ $cicloAtivo->data_inicio->format('d/m/Y') }} → {{ $cicloAtivo->data_fim->format('d/m/Y') }}
                · Prazo contestação: {{ $cicloAtivo->prazo_contestacao_dias }} dias
            </p>
        </div>
    </div>
    @endif

    {{-- Header com botão --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Todos os ciclos</h2>
            <p class="text-sm text-[#727785]">{{ $ciclos->count() }} ciclo(s) cadastrado(s)</p>
        </div>
        <button
            wire:click="openCreate"
            class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
        >
            <span class="material-symbols-outlined text-lg">add</span>
            Novo Ciclo
        </button>
    </div>

    {{-- Lista de ciclos --}}
    <div class="space-y-3">
        @forelse($ciclos as $ciclo)
        <div
            class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] hover:shadow-[0_20px_60px_rgba(23,28,31,0.10)] hover:-translate-y-0.5 transition-all duration-300 group flex items-center gap-4"
            style="animation-delay: {{ $loop->index * 50 }}ms"
        >
            {{-- Status icon --}}
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                {{ $ciclo->isAtivo() ? 'bg-[#00855b]/10' : 'bg-[#f0f4f8]' }}">
                <span class="material-symbols-outlined text-xl {{ $ciclo->isAtivo() ? 'text-[#006947]' : 'text-[#727785]' }}">
                    {{ $ciclo->isAtivo() ? 'play_circle' : 'pause_circle' }}
                </span>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-sm">{{ $ciclo->nome }}</h3>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold
                        {{ $ciclo->isAtivo() ? 'bg-[#00855b]/10 text-[#006947] border border-[#006947]/20' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $ciclo->isAtivo() ? 'bg-[#006947]' : 'bg-amber-500' }}"></span>
                        {{ $ciclo->isAtivo() ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="flex items-center gap-4 mt-1 text-xs text-[#727785] flex-wrap">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        {{ $ciclo->data_inicio->format('d/m/Y') }} → {{ $ciclo->data_fim->format('d/m/Y') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">timer</span>
                        {{ $ciclo->prazo_contestacao_dias }} dias para contestar
                    </span>
                    @if($ciclo->criador)
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">person</span>
                        Criado por {{ $ciclo->criador->nome }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 shrink-0">
                @if($ciclo->isAtivo())
                <button
                    wire:click="desativar('{{ $ciclo->id }}')"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors"
                >
                    <span class="material-symbols-outlined text-sm">pause</span>
                    Desativar
                </button>
                @else
                <button
                    wire:click="ativar('{{ $ciclo->id }}')"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#00855b]/10 text-[#006947] hover:bg-[#00855b]/20 transition-colors"
                >
                    <span class="material-symbols-outlined text-sm">play_arrow</span>
                    Ativar
                </button>
                <button
                    wire:click="confirmDelete('{{ $ciclo->id }}')"
                    class="w-8 h-8 rounded-lg hover:bg-[#ffdad6] flex items-center justify-center transition-colors"
                    title="Excluir"
                >
                    <span class="material-symbols-outlined text-[#ba1a1a] text-base">delete</span>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
            <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">event_busy</span>
            <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhum ciclo cadastrado</p>
            <p class="text-sm text-[#727785]">Crie um ciclo de avaliação para iniciar o processo.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- Modal criar ciclo --}}
<div x-data x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-lg ring-1 ring-black/[0.06]">

        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">event</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Novo Ciclo de Avaliação</h2>
            </div>
            <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-3">
                <span class="material-symbols-outlined text-amber-600 text-lg shrink-0 mt-0.5">info</span>
                <p class="text-xs text-amber-700">O ciclo será criado como <strong>inativo</strong>. Ative-o quando estiver pronto para iniciar as avaliações. Só pode haver um ciclo ativo por vez.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Nome do ciclo <span class="text-[#ba1a1a]">*</span></label>
                <input wire:model="nome" type="text" placeholder="Ex: Ciclo de Avaliação 2025/1"
                    class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('nome') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                @error('nome') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Data início <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="data_inicio" type="date"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('data_inicio') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('data_inicio') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Data término <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="data_fim" type="date"
                        class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('data_fim') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('data_fim') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">
                    Prazo para contestação (dias) <span class="text-[#ba1a1a]">*</span>
                </label>
                <input wire:model="prazo_contestacao_dias" type="number" min="1" max="90"
                    class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('prazo_contestacao_dias') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                @error('prazo_contestacao_dias') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-[#727785] mt-1">Número de dias após o envio da avaliação em que o servidor pode contestar.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors">Cancelar</button>
            <button wire:click="save" wire:loading.attr="disabled" class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-base">add</span>
                Criar Ciclo
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
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Excluir ciclo?</h3>
        <p class="text-sm text-[#727785] mb-6">Ciclos com avaliações vinculadas não podem ser excluídos.</p>
        <div class="flex gap-3">
            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] border border-[#c2c6d6] hover:bg-[#f0f4f8] transition-colors">Cancelar</button>
            <button wire:click="delete" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors">Excluir</button>
        </div>
    </div>
</div>
</div>
