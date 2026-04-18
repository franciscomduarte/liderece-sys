@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerencie as configurações do sistema')

<div>
<div class="space-y-6">

    @include('layouts.components.config-subnav')

    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">security</span>
                </div>
                <div>
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Segurança</h2>
                    <p class="text-xs text-[#727785] mt-0.5">Controle de acesso e políticas de sessão</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 space-y-6 max-w-xl">

            {{-- Toggle 2FA --}}
            <div class="flex items-start justify-between gap-6 py-1">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f0f4f8] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#424754] text-lg">verified_user</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#171c1f]">Autenticação em dois fatores (2FA)</p>
                        <p class="text-xs text-[#727785] mt-0.5">Quando ativado, usuários precisarão confirmar o login via e-mail ou app autenticador.</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Funcionalidade futura — ainda não operacional
                        </span>
                    </div>
                </div>
                <button
                    wire:click="$toggle('auth_dois_fatores')"
                    class="relative shrink-0 w-11 h-6 rounded-full transition-colors duration-200
                        {{ $auth_dois_fatores ? 'bg-[#0058be]' : 'bg-[#c2c6d6]' }}"
                    role="switch">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200
                        {{ $auth_dois_fatores ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </div>

            <div class="border-t border-[#eaeef2] pt-6">
                <label class="block text-xs font-bold text-[#424754] uppercase tracking-widest mb-1.5">
                    Tempo de expiração da sessão <span class="text-[#ba1a1a]">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" wire:model="sessao_expira_minutos" min="5" max="480"
                        class="w-32 px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('sessao_expira_minutos') border-[#ba1a1a] bg-[#fff8f7] @else border-[#c2c6d6] bg-[#f6fafe] @enderror">
                    <span class="text-sm text-[#424754] font-medium">minutos</span>
                    @if($sessao_expira_minutos >= 60)
                    <span class="text-xs text-[#727785]">({{ floor($sessao_expira_minutos / 60) }}h{{ $sessao_expira_minutos % 60 > 0 ? ' ' . ($sessao_expira_minutos % 60) . 'min' : '' }})</span>
                    @endif
                </div>
                @error('sessao_expira_minutos')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-[#727785] mt-1.5">Após esse período sem atividade, o usuário será desconectado automaticamente. Mínimo: 5 min · Máximo: 480 min (8h).</p>

                {{-- Presets --}}
                <div class="flex gap-2 mt-3 flex-wrap">
                    @foreach([15 => '15 min', 30 => '30 min', 60 => '1 hora', 120 => '2 horas', 480 => '8 horas'] as $val => $label)
                    <button wire:click="$set('sessao_expira_minutos', {{ $val }})"
                        class="px-3 py-1 rounded-lg text-xs font-semibold transition-colors
                            {{ $sessao_expira_minutos == $val ? 'bg-[#0058be] text-white' : 'bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed]' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
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

