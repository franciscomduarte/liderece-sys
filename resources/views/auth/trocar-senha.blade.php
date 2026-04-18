<x-layouts.auth>
    @section('title', 'Criar nova senha')

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.08)] p-8">

            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-amber-600 text-2xl">lock_reset</span>
            </div>

            <h2 class="font-['Manrope'] font-bold text-2xl text-[#171c1f] mb-1">Crie sua senha</h2>
            <p class="text-[#727785] text-sm mb-8">
                Este é seu primeiro acesso. Defina uma senha segura para continuar.
            </p>

            @if(session('error'))
            <div class="flex items-center gap-3 bg-[#ffdad6] border border-[#93000a]/20 rounded-xl px-4 py-3 mb-6">
                <span class="material-symbols-outlined text-[#ba1a1a] text-lg">error</span>
                <p class="text-[#93000a] text-sm font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('trocar-senha.store') }}" class="space-y-5">
                @csrf

                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold text-[#171c1f] mb-2">Nova senha</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-xl">lock</span>
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="Mínimo 8 caracteres"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-[#c2c6d6] bg-[#f6fafe] text-sm
                                   focus:outline-none focus:border-[#0058be] focus:ring-2 focus:ring-[#0058be]/20 transition-all
                                   @error('password') border-[#ba1a1a] @enderror">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#727785]">
                            <span class="material-symbols-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-[#ba1a1a] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-semibold text-[#171c1f] mb-2">Confirmar senha</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-xl">lock_clock</span>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="Repita a senha"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-[#c2c6d6] bg-[#f6fafe] text-sm
                                   focus:outline-none focus:border-[#0058be] focus:ring-2 focus:ring-[#0058be]/20 transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#727785]">
                            <span class="material-symbols-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white py-3 rounded-xl
                           font-['Manrope'] font-bold text-sm shadow-lg shadow-[#0058be]/20
                           hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Definir senha e acessar
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
