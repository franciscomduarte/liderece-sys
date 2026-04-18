<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #171c1f; margin: 0; padding: 24px; }
    h1 { font-size: 18px; font-weight: bold; margin: 0 0 4px; color: #0058be; }
    .subtitle { font-size: 12px; color: #727785; margin-bottom: 24px; }
    .kpis { display: flex; gap: 16px; margin-bottom: 24px; }
    .kpi { flex: 1; background: #f6fafe; border-radius: 8px; padding: 12px; border-left: 3px solid #0058be; }
    .kpi-value { font-size: 20px; font-weight: bold; }
    .kpi-label { font-size: 10px; color: #727785; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    thead tr { background: #f0f4f8; }
    th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #424754; }
    td { padding: 8px 10px; border-bottom: 1px solid #eaeef2; }
    tr:last-child td { border-bottom: none; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-weight: bold; }
    .badge-green { background: #d4f4e7; color: #006947; }
    .badge-blue { background: #d8e2ff; color: #004395; }
    .badge-amber { background: #fff3cd; color: #92670d; }
    .footer { margin-top: 24px; font-size: 10px; color: #727785; text-align: right; }
</style>
</head>
<body>
<h1>Relatório Geral de Desempenho</h1>
<p class="subtitle">Ciclo: {{ $ciclo->nome }} · Gerado em {{ now()->format('d/m/Y \à\s H:i') }}</p>

<div class="kpis">
    <div class="kpi">
        <div class="kpi-value">{{ $resumo['total_avaliacoes'] }}</div>
        <div class="kpi-label">Avaliações</div>
    </div>
    <div class="kpi">
        <div class="kpi-value">{{ $resumo['percentual_concluido'] }}%</div>
        <div class="kpi-label">Concluído</div>
    </div>
    <div class="kpi">
        <div class="kpi-value">{{ $resumo['media_geral'] > 0 ? number_format($resumo['media_geral'], 1) : '—' }}</div>
        <div class="kpi-label">Média Geral</div>
    </div>
    <div class="kpi">
        <div class="kpi-value">{{ $resumo['contestacoes_pendentes'] }}</div>
        <div class="kpi-label">Contestações Pendentes</div>
    </div>
</div>

@if(count($porArea) > 0)
<h2 style="font-size:13px; margin-bottom:8px;">Médias por Área</h2>
<table>
    <thead><tr><th>Área</th><th>Avaliações Enviadas</th><th>Média</th></tr></thead>
    <tbody>
        @foreach($porArea as $a)
        <tr>
            <td>{{ $a['nome'] }}</td>
            <td>{{ $a['total'] }}</td>
            <td>
                <span class="badge {{ $a['media'] >= 4 ? 'badge-green' : ($a['media'] >= 3 ? 'badge-blue' : 'badge-amber') }}">
                    {{ number_format($a['media'], 1) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(count($ranking) > 0)
<h2 style="font-size:13px; margin: 20px 0 8px;">Ranking de Servidores — Top 10</h2>
<table>
    <thead><tr><th>#</th><th>Servidor</th><th>Área</th><th>Competências</th><th>Média</th></tr></thead>
    <tbody>
        @foreach($ranking as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $s['nome'] }}</strong><br><span style="color:#727785">{{ $s['cargo'] }}</span></td>
            <td>{{ $s['area_nome'] }}</td>
            <td>{{ $s['total_competencias'] }}</td>
            <td>
                <span class="badge {{ $s['media_geral'] >= 4 ? 'badge-green' : ($s['media_geral'] >= 3 ? 'badge-blue' : 'badge-amber') }}">
                    {{ number_format($s['media_geral'], 1) }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">SGC — Sistema de Gestão de Competências</div>
</body>
</html>
