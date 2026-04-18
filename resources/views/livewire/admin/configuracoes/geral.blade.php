@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerencie as configurações do sistema')

<div>
<div class="space-y-6">

    @include('layouts.components.config-subnav')

    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">tune</span>
                </div>
                <div>
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Configurações Gerais</h2>
                    <p class="text-xs text-[#727785] mt-0.5">Informações básicas da organização</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 space-y-5 max-w-xl">

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-widest mb-1.5">
                    Nome da organização <span class="text-[#ba1a1a]">*</span>
                </label>
                <input type="text" wire:model="nome_organizacao"
                    class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('nome_organizacao') border-[#ba1a1a] bg-[#fff8f7] @else border-[#c2c6d6] bg-[#f6fafe] @enderror"
                    placeholder="Ex: Ministério da Educação">
                @error('nome_organizacao')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-[#727785] mt-1">Exibido nos relatórios e cabeçalhos do sistema.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-widest mb-1.5">
                    Escala máxima de avaliação <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <select wire:model="escala_maxima"
                        class="px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-[#f6fafe] @error('escala_maxima') border-[#ba1a1a] @enderror">
                        @foreach(range(3, 10) as $v)
                        <option value="{{ $v }}">{{ $v }} pontos</option>
                        @endforeach
                    </select>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= $escala_maxima; $i++)
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i <= 2 ? 'bg-[#ffdad6] text-[#93000a]' : ($i >= $escala_maxima - 1 ? 'bg-[#6ffbbe]/30 text-[#006947]' : 'bg-[#d8e2ff] text-[#004395]') }}">
                            {{ $i }}
                        </div>
                        @endfor
                    </div>
                </div>
                @error('escala_maxima')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-[#727785] mt-1">Define a pontuação máxima nos formulários de avaliação. Alterar esta configuração afeta novos ciclos.</p>
            </div>

            <div class="pt-2">
                <button wire:click="salvar" wire:loading.attr="disabled"
                    class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-60 disabled:scale-100">
                    <span wire:loading.remove class="material-symbols-outlined text-base">save</span>
                    <span wire:loading><svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg></span>
                    <span wire:loading.remove>Salvar configurações</span>
                    <span wire:loading>Salvando...</span>
                </button>
            </div>
        </div>
    </div>

</div>
</div>

