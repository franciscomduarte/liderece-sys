@section('page-title', 'Meu Perfil')
@section('page-subtitle', 'Suas informações e configurações de acesso')

<div>
<div class="space-y-6 max-w-2xl">

    {{-- Dados do servidor --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Dados cadastrais</h2>
            <p class="text-xs text-[#727785] mt-0.5">Informações gerenciadas pelo administrador do sistema</p>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shrink-0">
                    <span class="text-white font-['Manrope'] font-extrabold text-2xl">
                        {{ strtoupper(substr($servidor->nome, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <p class="font-['Manrope'] font-bold text-[#171c1f] text-lg">{{ $servidor->nome }}</p>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold mt-1
                        @if($servidor->perfil === 'admin') bg-[#d8e2ff] text-[#004395]
                        @elseif($servidor->perfil === 'gestor') bg-[#dee2f7] text-[#414657]
                        @else bg-[#6ffbbe]/20 text-[#002113] @endif">
                        {{ ucfirst($servidor->perfil) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <p class="text-xs font-bold text-[#424754] uppercase tracking-widest mb-1">Matrícula</p>
                    <p class="text-sm text-[#171c1f] font-medium">{{ $servidor->matricula }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#424754] uppercase tracking-widest mb-1">E-mail</p>
                    <p class="text-sm text-[#171c1f] font-medium">{{ $servidor->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#424754] uppercase tracking-widest mb-1">Cargo</p>
                    <p class="text-sm text-[#171c1f] font-medium">{{ $servidor->cargo }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#424754] uppercase tracking-widest mb-1">Área</p>
                    <p class="text-sm text-[#171c1f] font-medium">{{ $servidor->area?->nome ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alterar senha --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:100ms">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Alterar senha</h2>
            <p class="text-xs text-[#727785] mt-0.5">Mínimo 8 caracteres com letras e números</p>
        </div>

        <form wire:submit="salvarSenha" class="px-6 py-5 space-y-4">

            <div>
                <label class="text-xs font-bold text-[#424754] uppercase tracking-widest block mb-1.5">
                    Senha atual
                </label>
                <input type="password" wire:model="senhaAtual"
                    class="w-full bg-[#f6fafe] border @error('senhaAtual') border-[#ba1a1a] @else border-[#c2c6d6] @enderror rounded-xl px-4 py-2.5 text-sm text-[#171c1f] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all"
                    placeholder="Digite sua senha atual">
                @error('senhaAtual')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-[#424754] uppercase tracking-widest block mb-1.5">
                    Nova senha
                </label>
                <input type="password" wire:model="novaSenha"
                    class="w-full bg-[#f6fafe] border @error('novaSenha') border-[#ba1a1a] @else border-[#c2c6d6] @enderror rounded-xl px-4 py-2.5 text-sm text-[#171c1f] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all"
                    placeholder="Mínimo 8 caracteres">
                @error('novaSenha')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-[#424754] uppercase tracking-widest block mb-1.5">
                    Confirmar nova senha
                </label>
                <input type="password" wire:model="confirmaSenha"
                    class="w-full bg-[#f6fafe] border @error('confirmaSenha') border-[#ba1a1a] @else border-[#c2c6d6] @enderror rounded-xl px-4 py-2.5 text-sm text-[#171c1f] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all"
                    placeholder="Repita a nova senha">
                @error('confirmaSenha')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <span class="material-symbols-outlined text-base align-middle">lock_reset</span>
                    </span>
                    <span wire:loading>
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove>Alterar senha</span>
                    <span wire:loading>Salvando...</span>
                </button>
            </div>
        </form>
    </div>

</div>
</div>
