@section('page-title', 'Relatórios')
@section('page-subtitle', 'Análise de desempenho e exportação de dados')

<div>
<div class="space-y-6">

    {{-- Filtro de ciclo + exportação --}}
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
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('admin.relatorios.exportar.csv', ['ciclo_id' => $ciclo->id]) }}"
               class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed] transition-colors">
                <span class="material-symbols-outlined text-base">table_view</span>
                CSV
            </a>
            <a href="{{ route('admin.relatorios.exportar.pdf', ['ciclo_id' => $ciclo->id]) }}"
               class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                PDF
            </a>
        </div>
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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:50ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#d8e2ff] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#004395] text-lg">assignment</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Avaliações</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $resumo['total_avaliacoes'] }}</p>
            <p class="text-xs text-[#727785] mt-1">{{ $resumo['enviadas'] }} enviadas</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:100ms">
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

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:150ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#dee2f7] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#414657] text-lg">stars</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Média Geral</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">
                {{ $resumo['media_geral'] > 0 ? number_format($resumo['media_geral'], 1) : '—' }}
            </p>
            <p class="text-xs text-[#727785] mt-1">escala de 1 a 5</p>
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-4 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:200ms">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-amber-600 text-lg">gavel</span>
                </div>
                <span class="text-xs font-bold text-[#424754] uppercase tracking-widest">Contestações</span>
            </div>
            <p class="font-['Manrope'] font-extrabold text-3xl text-[#171c1f]">{{ $resumo['contestacoes_pendentes'] }}</p>
            <p class="text-xs text-[#727785] mt-1">pendentes de resposta</p>
        </div>
    </div>
    @endif

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="float-in lg:col-span-2 bg-white rounded-2xl px-5 py-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:250ms">
            <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f] mb-4">Média por área</h3>
            @if(count($porArea) > 0)
            <div class="relative h-52">
                <canvas id="chart-areas" wire:ignore></canvas>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-52 text-center">
                <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">bar_chart</span>
                <p class="text-xs text-[#727785] mt-2">Nenhum dado disponível</p>
            </div>
            @endif
        </div>

        <div class="float-in bg-white rounded-2xl px-5 py-5 shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04]" style="animation-delay:300ms">
            <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f] mb-4">Status das avaliações</h3>
            @if(($status['rascunho'] + $status['enviada']) > 0)
            <div class="relative h-36">
                <canvas id="chart-status" wire:ignore></canvas>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-[#424754]">
                        <span class="w-3 h-3 rounded-sm inline-block bg-[#00855b]"></span>Enviadas
                    </span>
                    <span class="font-bold text-[#171c1f]">{{ $status['enviada'] }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-[#424754]">
                        <span class="w-3 h-3 rounded-sm inline-block bg-[#c2c6d6]"></span>Rascunho
                    </span>
                    <span class="font-bold text-[#171c1f]">{{ $status['rascunho'] }}</span>
                </div>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-36 text-center">
                <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">donut_large</span>
                <p class="text-xs text-[#727785] mt-2">Sem avaliações</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Ranking --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden" style="animation-delay:350ms">
        <div class="px-5 py-4 border-b border-[#eaeef2] flex items-center justify-between">
            <h3 class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Ranking de servidores — top 10</h3>
            <span class="text-xs text-[#727785]">por média das competências</span>
        </div>
        @if(count($ranking) > 0)
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#f0f4f8] text-xs font-bold uppercase tracking-widest text-[#424754]">
                    <th class="px-5 py-3 text-left w-8">#</th>
                    <th class="px-5 py-3 text-left">Servidor</th>
                    <th class="px-5 py-3 text-left hidden md:table-cell">Área</th>
                    <th class="px-5 py-3 text-center hidden sm:table-cell">Competências</th>
                    <th class="px-5 py-3 text-right">Média</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#eaeef2]">
                @foreach($ranking as $i => $s)
                <tr class="hover:bg-[#f6fafe] transition-colors">
                    <td class="px-5 py-3.5 text-[#727785] font-bold">{{ $i + 1 }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-[#171c1f]">{{ $s['nome'] }}</p>
                        <p class="text-xs text-[#727785]">{{ $s['cargo'] }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-[#424754] hidden md:table-cell">{{ $s['area_nome'] }}</td>
                    <td class="px-5 py-3.5 text-center text-[#424754] hidden sm:table-cell">{{ $s['total_competencias'] }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl font-bold text-sm
                            {{ $s['media_geral'] >= 4 ? 'bg-[#6ffbbe]/20 text-[#006947]' : ($s['media_geral'] >= 3 ? 'bg-[#d8e2ff] text-[#004395]' : 'bg-amber-50 text-amber-700') }}">
                            {{ number_format($s['media_geral'], 1) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-5 py-12 text-center">
            <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">leaderboard</span>
            <p class="text-sm text-[#727785] mt-2">Nenhuma avaliação enviada neste ciclo.</p>
        </div>
        @endif
    </div>

    @endif
</div>
</div>

@if($ciclo)
<script>
window.addEventListener('load', function () {
    var areasData = @json($porArea);
    var statusData = @json($status);

    if (typeof Chart === 'undefined') return;

    if (areasData.length > 0) {
        var ctxAreas = document.getElementById('chart-areas');
        if (ctxAreas) {
            new Chart(ctxAreas, {
                type: 'bar',
                data: {
                    labels: areasData.map(function(a) { return a.nome; }),
                    datasets: [{
                        label: 'Média',
                        data: areasData.map(function(a) { return a.media; }),
                        backgroundColor: '#2170e4',
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { min: 0, max: 5, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f0f4f8' } },
                        x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                    }
                }
            });
        }
    }

    if ((statusData.rascunho + statusData.enviada) > 0) {
        var ctxStatus = document.getElementById('chart-status');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Enviadas', 'Rascunho'],
                    datasets: [{ data: [statusData.enviada, statusData.rascunho], backgroundColor: ['#00855b', '#e4e9ed'], borderWidth: 0 }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
            });
        }
    }
});
</script>
@endif
