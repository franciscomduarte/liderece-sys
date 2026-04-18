@section('page-title', 'Relatórios')
@section('page-subtitle', 'Análise de desempenho da sua área')

<div>
<div class="space-y-6">

    {{-- Filtro + exportação --}}
    <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="flex-1 min-w-0">
            <label class="text-xs font-bold text-[#424754] uppercase tracking-widest block mb-1.5">Ciclo de avaliação</label>
            <select wire:model.live="cicloId"
                class="w-full sm:w-72 bg-[#f6fafe] border border-[#c2c6d6] rounded-xl px-4 py-2.5 text-sm text-[#171c1f] font-medium focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all">
                <option value="">Selecione um ciclo...</option>
                @foreach($ciclos as $c)
                <option value="{{ $c->id }}" @selected($c->id === $cicloId)>{{ $c->nome }}</option>
                @endforeach
            </select>
        </div>
        @if($ciclo)
        <a href="{{ route('gestor.relatorios.exportar.csv', ['ciclo_id' => $ciclo->id]) }}"
           class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed] transition-colors shrink-0">
            <span class="material-symbols-outlined text-base">table_view</span>
            Exportar CSV
        </a>
        @endif
    </div>

    @if(!$ciclo)
    <div class="float-in bg-white rounded-2xl p-12 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6]">bar_chart</span>
        <p class="text-[#727785] text-sm mt-3">Selecione um ciclo para visualizar os relatórios.</p>
    </div>
    @else

    {{-- KPIs --}}
    @if($resumo)
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#d8e2ff] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#004395] text-lg">group</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Servidores</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $resumo['total_servidores'] }}</p>
            <p class="text-xs text-[#727785] mt-1">na sua área</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#dee2f7] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#414657] text-lg">stars</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Média da Área</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">
                {{ $resumo['media_area'] > 0 ? number_format($resumo['media_area'], 1) : '—' }}
            </p>
            <p class="text-xs text-[#727785] mt-1">escala de 1 a 5</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:150ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#6ffbbe]/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#006947] text-lg">percent</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Concluído</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $resumo['percentual_concluido'] }}%</p>
            <div class="mt-2 h-1.5 rounded-full bg-[#f0f4f8] overflow-hidden">
                <div class="h-full rounded-full bg-[#00855b] transition-all" style="width: {{ $resumo['percentual_concluido'] }}%"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabela de servidores --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:200ms">
        <div class="px-5 py-4 border-b border-[#eaeef2]">
            <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Servidores da área</h3>
        </div>
        @if($servidores->isNotEmpty())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#f0f4f8] text-xs font-bold uppercase tracking-widest text-[#424754]">
                    <th class="px-5 py-3 text-left">Servidor</th>
                    <th class="px-5 py-3 text-center hidden sm:table-cell">Avaliações</th>
                    <th class="px-5 py-3 text-center hidden sm:table-cell">Enviadas</th>
                    <th class="px-5 py-3 text-center hidden md:table-cell">Contestações</th>
                    <th class="px-5 py-3 text-right">Média</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#eaeef2]">
                @foreach($servidores as $s)
                <tr class="hover:bg-[#f6fafe] transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-[#171c1f]">{{ $s['nome'] }}</p>
                        <p class="text-xs text-[#727785]">{{ $s['cargo'] }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-center text-[#424754] hidden sm:table-cell">{{ $s['total_avaliacoes'] }}</td>
                    <td class="px-5 py-3.5 text-center hidden sm:table-cell">
                        @if($s['total_avaliacoes'] > 0)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold
                            {{ $s['enviadas'] === $s['total_avaliacoes'] ? 'text-[#006947]' : 'text-amber-600' }}">
                            {{ $s['enviadas'] }}/{{ $s['total_avaliacoes'] }}
                        </span>
                        @else
                        <span class="text-[#c2c6d6]">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center hidden md:table-cell">
                        @if($s['contestacoes'] > 0)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                            {{ $s['contestacoes'] }}
                        </span>
                        @else
                        <span class="text-[#c2c6d6]">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        @if($s['media_geral'] !== null)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl font-bold text-sm
                            {{ $s['media_geral'] >= 4 ? 'bg-[#6ffbbe]/20 text-[#006947]' : ($s['media_geral'] >= 3 ? 'bg-[#d8e2ff] text-[#004395]' : 'bg-amber-50 text-amber-700') }}">
                            {{ number_format($s['media_geral'], 1) }}
                        </span>
                        @else
                        <span class="text-xs text-[#c2c6d6]">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-5 py-12 text-center">
            <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">group_off</span>
            <p class="text-sm text-[#727785] mt-2">Nenhum servidor ativo na sua área.</p>
        </div>
        @endif
    </div>

    @endif
</div>
</div>
