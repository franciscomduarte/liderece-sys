@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerencie as configurações do sistema')

<div>
<div class="space-y-6">

    @include('layouts.components.config-subnav')

    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">notifications</span>
                </div>
                <div>
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Notificações</h2>
                    <p class="text-xs text-[#727785] mt-0.5">Controle quais eventos geram notificações para os usuários</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 divide-y divide-[#eaeef2]">

            @php
            $toggles = [
                ['model' => 'notif_avaliacao_pendente', 'label' => 'Avaliação pendente', 'desc' => 'Notifica o servidor quando uma nova autoavaliação estiver disponível no ciclo ativo.', 'icon' => 'edit_note'],
                ['model' => 'notif_nova_avaliacao',     'label' => 'Nova avaliação do gestor', 'desc' => 'Notifica o servidor quando o gestor concluir a avaliação de sua competência.', 'icon' => 'supervisor_account'],
                ['model' => 'notif_relatorio_mensal',   'label' => 'Relatório mensal', 'desc' => 'Envia um resumo mensal de progresso para administradores.', 'icon' => 'analytics'],
            ];
            @endphp

            @foreach($toggles as $toggle)
            <div class="py-4 flex items-center justify-between gap-6">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#f0f4f8] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[#424754] text-lg">{{ $toggle['icon'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#171c1f]">{{ $toggle['label'] }}</p>
                        <p class="text-xs text-[#727785] mt-0.5">{{ $toggle['desc'] }}</p>
                    </div>
                </div>
                <button
                    wire:click="$toggle('{{ $toggle['model'] }}')"
                    class="relative shrink-0 w-11 h-6 rounded-full transition-colors duration-200
                        {{ $this->{$toggle['model']} ? 'bg-[#0058be]' : 'bg-[#c2c6d6]' }}"
                    role="switch"
                    aria-checked="{{ $this->{$toggle['model']} ? 'true' : 'false' }}">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200
                        {{ $this->{$toggle['model']} ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </div>
            @endforeach

        </div>

        <div class="px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe]">
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

