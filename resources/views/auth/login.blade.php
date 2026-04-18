<x-layouts.auth>
    @section('title', 'Entrar')

    <div class="w-full max-w-md">

        {{-- Card de login --}}
        <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.08)] p-8">

            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shadow-lg shadow-[#0058be]/20">
                    <span class="material-symbols-outlined text-white text-2xl">workspace_premium</span>
                </div>
                <div>
                    <h1 class="font-['Manrope'] font-extrabold text-xl text-[#171c1f] leading-none">SGC</h1>
                    <p class="text-[#727785] text-xs mt-0.5">Sistema de Gestão de Competências</p>
                </div>
            </div>

            <h2 class="font-['Manrope'] font-bold text-2xl text-[#171c1f] mb-1">Bem-vindo de volta</h2>
            <p class="text-[#727785] text-sm mb-8">Acesse com suas credenciais institucionais</p>

            {{-- Erros --}}
            @if(session('error'))
            <div class="flex items-center gap-3 bg-[#ffdad6] border border-[#93000a]/20 rounded-xl px-4 py-3 mb-6">
                <span class="material-symbols-outlined text-[#ba1a1a] text-lg">error</span>
                <p class="text-[#93000a] text-sm font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- E-mail --}}
                <div>
                    <label class="block text-sm font-semibold text-[#171c1f] mb-2" for="email">
                        E-mail institucional
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-xl">mail</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="seu@orgao.gov.br"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#c2c6d6] bg-[#f6fafe] text-[#171c1f] text-sm
                                   focus:outline-none focus:border-[#0058be] focus:ring-2 focus:ring-[#0058be]/20 transition-all
                                   @error('email') border-[#ba1a1a] @enderror"
                        >
                    </div>
                    @error('email')
                    <p class="text-[#ba1a1a] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Senha --}}
                <div>
                    <label class="block text-sm font-semibold text-[#171c1f] mb-2" for="password">
                        Senha
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#727785] text-xl">lock</span>
                        <input
                            type="password"
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-[#c2c6d6] bg-[#f6fafe] text-[#171c1f] text-sm
                                   focus:outline-none focus:border-[#0058be] focus:ring-2 focus:ring-[#0058be]/20 transition-all
                                   @error('password') border-[#ba1a1a] @enderror"
                        >
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#727785] hover:text-[#424754]">
                            <span class="material-symbols-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-[#ba1a1a] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lembrar --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-[#c2c6d6] text-[#0058be]">
                    <label for="remember" class="text-sm text-[#424754]">Manter conectado</label>
                </div>

                {{-- Botão --}}
                <button type="submit"
                    class="w-full bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white py-3 rounded-xl
                           font-['Manrope'] font-bold text-sm shadow-lg shadow-[#0058be]/20
                           hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Entrar no sistema
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-[#727785] mt-6">
            Sistema de Gestão de Competências — Uso exclusivo de servidores autorizados
        </p>
    </div>
</x-layouts.auth>
