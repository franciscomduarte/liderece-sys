@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerencie as configurações do sistema')

<div>
<div class="space-y-6">

    @include('layouts.components.config-subnav')

    {{-- Resumo do banco --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]">
            <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#004395] text-lg">groups</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['servidores'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Servidores cadastrados</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="w-9 h-9 rounded-xl bg-[#dee2f7] flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#414657] text-lg">workspace_premium</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['competencias'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Competências cadastradas</p>
        </div>
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
            <div class="w-9 h-9 rounded-xl bg-[#6ffbbe]/20 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[#006947] text-lg">assignment_turned_in</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-2xl text-[#171c1f]">{{ $stats['avaliacoes'] }}</p>
            <p class="text-[#727785] text-xs mt-1">Avaliações registradas</p>
        </div>
    </div>

    {{-- Exportação --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:150ms">
        <div class="px-6 py-5 border-b border-[#eaeef2]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">download</span>
                </div>
                <div>
                    <h2 class="font-['Manrope'] font-bold text-[#171c1f]">Exportação de dados</h2>
                    <p class="text-xs text-[#727785] mt-0.5">Exporte os dados do sistema para análise externa</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 divide-y divide-[#eaeef2]">

            <div class="py-4 flex items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#6ffbbe]/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#006947] text-lg">table_chart</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#171c1f]">Relatório completo — CSV</p>
                        <p class="text-xs text-[#727785] mt-0.5">Todos os resultados de avaliações, médias e áreas. Compatível com Excel e Google Sheets.</p>
                    </div>
                </div>
                <a href="{{ route('admin.relatorios') }}"
                    class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border border-[#006947]/30 text-[#006947] hover:bg-[#6ffbbe]/20 transition-colors">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Ver Relatórios
                </a>
            </div>

            <div class="py-4 flex items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#ffdad6] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#93000a] text-lg">picture_as_pdf</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#171c1f]">Relatório completo — PDF</p>
                        <p class="text-xs text-[#727785] mt-0.5">Versão formatada para impressão e arquivamento oficial.</p>
                    </div>
                </div>
                <a href="{{ route('admin.relatorios') }}"
                    class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border border-[#ba1a1a]/30 text-[#ba1a1a] hover:bg-[#ffdad6] transition-colors">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Ver Relatórios
                </a>
            </div>

        </div>
    </div>

    {{-- Zona de perigo --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-[#ba1a1a]/20 overflow-hidden" style="animation-delay:200ms">
        <div class="px-6 py-5 border-b border-[#ffdad6]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#ffdad6] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#ba1a1a] text-lg">warning</span>
                </div>
                <div>
                    <h2 class="font-['Manrope'] font-bold text-[#ba1a1a]">Zona de risco</h2>
                    <p class="text-xs text-[#727785] mt-0.5">Ações irreversíveis — use com cautela</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-[#171c1f]">Dados de teste / desenvolvimento</p>
                    <p class="text-xs text-[#727785] mt-0.5">Execute o seeder de desenvolvimento apenas em ambientes locais. Em produção esse comando retorna erro.</p>
                    <div class="mt-2 flex items-center gap-2 bg-[#f0f4f8] rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined text-[#424754] text-sm">terminal</span>
                        <code class="text-xs text-[#424754] font-mono">php artisan db:seed --class=DevelopmentSeeder</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

