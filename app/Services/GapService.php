<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use Illuminate\Support\Collection;

class GapService
{
    // ── Helpers estáticos ─────────────────────────────────────────────────────

    public static function descricaoNivel(int $nivel): string
    {
        return match($nivel) {
            1 => 'Inicial',
            2 => 'Básico',
            3 => 'Proficiente',
            4 => 'Avançado',
            5 => 'Referência',
            default => '—',
        };
    }

    /**
     * Classifica o gap em três categorias de urgência.
     * Retorna: 'adequado' | 'leve' | 'estrategico'
     */
    public static function classificar(int $gap): string
    {
        return match(true) {
            $gap === 0  => 'adequado',
            $gap === 1  => 'leve',
            default     => 'estrategico',
        };
    }

    public static function labelClassificacao(string $classe): string
    {
        return match($classe) {
            'adequado'   => 'Adequado',
            'leve'       => 'Desenvolvimento leve',
            'estrategico'=> 'Prioridade estratégica',
            default      => '—',
        };
    }

    // ── Queries por escopo ────────────────────────────────────────────────────

    /**
     * Retorna o gap de cada competência de um servidor no ciclo.
     * Cada item: competencia, nivel_esperado, nivel_atual, gap, classificacao
     */
    public function gapsDoServidor(Servidor $servidor, Ciclo $ciclo): Collection
    {
        if (! $servidor->area_id) {
            return collect();
        }

        $competencias = Competencia::ativas()
            ->whereHas('areas', fn($q) => $q->where('areas.id', $servidor->area_id))
            ->with(['areas' => fn($q) => $q->where('areas.id', $servidor->area_id)])
            ->orderBy('nome')
            ->get();

        $avaliacoesEnviadas = Avaliacao::where('ciclo_id', $ciclo->id)
            ->where('servidor_id', $servidor->id)
            ->where('tipo', 'area')
            ->where('status', 'enviada')
            ->get()
            ->keyBy('competencia_id');

        return $competencias->map(function (Competencia $c) use ($avaliacoesEnviadas) {
            $nivelEsperado = (int) ($c->areas->first()?->pivot->nivel_esperado ?? 3);
            $avaliacao     = $avaliacoesEnviadas->get($c->id);
            $nivelAtual    = $avaliacao ? AvaliacaoService::nivelProficiencia($avaliacao->media) : null;
            $gap           = $nivelAtual !== null ? max(0, $nivelEsperado - $nivelAtual) : null;
            $classificacao = $gap !== null ? self::classificar($gap) : null;

            return [
                'competencia'   => $c,
                'nivel_esperado'=> $nivelEsperado,
                'nivel_atual'   => $nivelAtual,
                'media'         => $avaliacao?->media,
                'gap'           => $gap,
                'classificacao' => $classificacao,
            ];
        });
    }

    /**
     * Retorna gaps de todos os servidores (perfil='servidor') de uma área.
     * Resultado: Collection de ['servidor' => Servidor, 'gaps' => Collection, 'resumo' => [...]]
     */
    public function gapsDaArea(string $areaId, Ciclo $ciclo): Collection
    {
        $servidores = Servidor::where('area_id', $areaId)
            ->where('perfil', 'servidor')
            ->where('status', 'ativo')
            ->orderBy('nome')
            ->get();

        return $servidores->map(function (Servidor $s) use ($ciclo) {
            $gaps = $this->gapsDoServidor($s, $ciclo);
            return [
                'servidor' => $s,
                'gaps'     => $gaps,
                'resumo'   => $this->resumirGaps($gaps),
            ];
        });
    }

    /**
     * Resumo institucional: totais por classificação, e breakdown por área.
     */
    public function resumoGeral(Ciclo $ciclo): array
    {
        $servidores = Servidor::where('status', 'ativo')
            ->where('perfil', 'servidor')
            ->get();

        $totais = ['adequado' => 0, 'leve' => 0, 'estrategico' => 0, 'sem_avaliacao' => 0];

        foreach ($servidores as $s) {
            $gaps = $this->gapsDoServidor($s, $ciclo);
            foreach ($gaps as $item) {
                if ($item['classificacao'] === null) {
                    $totais['sem_avaliacao']++;
                } else {
                    $totais[$item['classificacao']]++;
                }
            }
        }

        $totais['total'] = array_sum($totais);

        return $totais;
    }

    // ── Helpers internos ──────────────────────────────────────────────────────

    public function resumirGaps(Collection $gaps): array
    {
        $resumo = ['adequado' => 0, 'leve' => 0, 'estrategico' => 0, 'sem_avaliacao' => 0];
        foreach ($gaps as $item) {
            $key = $item['classificacao'] ?? 'sem_avaliacao';
            $resumo[$key]++;
        }
        return $resumo;
    }
}
