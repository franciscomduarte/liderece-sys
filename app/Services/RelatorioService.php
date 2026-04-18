<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Contestacao;
use App\Models\Servidor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RelatorioService
{
    // ── Admin ──────────────────────────────────────────────────────────────────

    public function resumoGeral(Ciclo $ciclo): array
    {
        $base = Avaliacao::where('ciclo_id', $ciclo->id)->where('tipo', 'area');
        $total = $base->count();
        $enviadas = (clone $base)->where('status', 'enviada');
        $qtdEnviadas = $enviadas->count();

        return [
            'total_avaliacoes'       => $total,
            'enviadas'               => $qtdEnviadas,
            'media_geral'            => $qtdEnviadas > 0 ? round((float) $enviadas->avg('media'), 1) : 0.0,
            'contestacoes_pendentes' => Contestacao::where('status', 'pendente')
                ->whereHas('avaliacao', fn ($q) => $q->where('ciclo_id', $ciclo->id))
                ->count(),
            'percentual_concluido'   => $total > 0 ? (int) round(($qtdEnviadas / $total) * 100) : 0,
        ];
    }

    public function mediaPorArea(Ciclo $ciclo): array
    {
        return DB::table('avaliacoes')
            ->join('servidores', 'avaliacoes.servidor_id', '=', 'servidores.id')
            ->join('areas', 'servidores.area_id', '=', 'areas.id')
            ->where('avaliacoes.ciclo_id', $ciclo->id)
            ->where('avaliacoes.tipo', 'area')
            ->where('avaliacoes.status', 'enviada')
            ->whereNotNull('avaliacoes.media')
            ->groupBy('areas.id', 'areas.nome')
            ->selectRaw('areas.nome, AVG(avaliacoes.media) as media, COUNT(avaliacoes.id) as total')
            ->orderByDesc('media')
            ->get()
            ->map(fn ($r) => [
                'nome'  => $r->nome,
                'media' => round((float) $r->media, 1),
                'total' => (int) $r->total,
            ])
            ->toArray();
    }

    public function distribuicaoStatus(Ciclo $ciclo): array
    {
        $counts = Avaliacao::where('ciclo_id', $ciclo->id)
            ->where('tipo', 'area')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'rascunho' => (int) ($counts['rascunho'] ?? 0),
            'enviada'  => (int) ($counts['enviada'] ?? 0),
        ];
    }

    public function rankingServidores(Ciclo $ciclo, int $limit = 10): array
    {
        return DB::table('avaliacoes')
            ->join('servidores', 'avaliacoes.servidor_id', '=', 'servidores.id')
            ->leftJoin('areas', 'servidores.area_id', '=', 'areas.id')
            ->where('avaliacoes.ciclo_id', $ciclo->id)
            ->where('avaliacoes.tipo', 'area')
            ->where('avaliacoes.status', 'enviada')
            ->whereNotNull('avaliacoes.media')
            ->groupBy('servidores.id', 'servidores.nome', 'servidores.cargo', 'areas.nome')
            ->selectRaw('servidores.nome, servidores.cargo, areas.nome as area_nome, AVG(avaliacoes.media) as media_geral, COUNT(avaliacoes.id) as total_competencias')
            ->orderByDesc('media_geral')
            ->limit($limit)
            ->get()
            ->map(fn ($r) => [
                'nome'               => $r->nome,
                'cargo'              => $r->cargo,
                'area_nome'          => $r->area_nome ?? '—',
                'media_geral'        => round((float) $r->media_geral, 1),
                'total_competencias' => (int) $r->total_competencias,
            ])
            ->toArray();
    }

    // ── Gestor ─────────────────────────────────────────────────────────────────

    public function resumoArea(Ciclo $ciclo, Area $area): array
    {
        $servidoresIds = Servidor::where('area_id', $area->id)->pluck('id');
        $base = Avaliacao::where('ciclo_id', $ciclo->id)
            ->where('tipo', 'area')
            ->whereIn('servidor_id', $servidoresIds);

        $total = $base->count();
        $enviadas = (clone $base)->where('status', 'enviada');
        $qtdEnviadas = $enviadas->count();

        return [
            'total_servidores'       => $servidoresIds->count(),
            'total_avaliacoes'       => $total,
            'enviadas'               => $qtdEnviadas,
            'media_area'             => $qtdEnviadas > 0 ? round((float) $enviadas->avg('media'), 1) : 0.0,
            'contestacoes_pendentes' => Contestacao::where('status', 'pendente')
                ->whereHas('avaliacao', fn ($q) => $q->where('ciclo_id', $ciclo->id)->whereIn('servidor_id', $servidoresIds))
                ->count(),
            'percentual_concluido'   => $total > 0 ? (int) round(($qtdEnviadas / $total) * 100) : 0,
        ];
    }

    public function servidoresDaArea(Ciclo $ciclo, Area $area): Collection
    {
        $servidores = Servidor::where('area_id', $area->id)
            ->where('perfil', 'servidor')
            ->where('status', 'ativo')
            ->orderBy('nome')
            ->get();

        return $servidores->map(function (Servidor $servidor) use ($ciclo) {
            $avaliacoes = Avaliacao::where('ciclo_id', $ciclo->id)
                ->where('servidor_id', $servidor->id)
                ->where('tipo', 'area')
                ->get();

            $enviadas = $avaliacoes->where('status', 'enviada');
            $mediaGeral = $enviadas->isNotEmpty()
                ? round($enviadas->avg('media'), 1)
                : null;

            $contestacoes = Contestacao::whereIn('avaliacao_id', $avaliacoes->pluck('id'))->count();

            return [
                'id'                  => $servidor->id,
                'nome'                => $servidor->nome,
                'cargo'               => $servidor->cargo,
                'total_avaliacoes'    => $avaliacoes->count(),
                'enviadas'            => $enviadas->count(),
                'media_geral'         => $mediaGeral,
                'contestacoes'        => $contestacoes,
            ];
        });
    }

    // ── Servidor ───────────────────────────────────────────────────────────────

    public function historicoServidor(Servidor $servidor): Collection
    {
        return Avaliacao::where('servidor_id', $servidor->id)
            ->where('tipo', 'area')
            ->where('status', 'enviada')
            ->with(['ciclo', 'competencia', 'contestacao'])
            ->orderByDesc('enviada_at')
            ->get()
            ->groupBy('ciclo_id')
            ->map(function ($avaliacoes) {
                $ciclo = $avaliacoes->first()->ciclo;
                return [
                    'ciclo'      => $ciclo,
                    'avaliacoes' => $avaliacoes,
                    'media_ciclo' => round($avaliacoes->avg('media'), 1),
                ];
            })
            ->values();
    }
}
