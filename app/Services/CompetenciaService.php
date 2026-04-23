<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use Illuminate\Support\Facades\DB;

class CompetenciaService
{
    /**
     * @param array<string> $areaIds
     * @param array<string, int> $niveisEsperados  [area_id => nivel_esperado]
     */
    public function store(array $data, array $itens = [], array $areaIds = [], array $niveisEsperados = []): Competencia
    {
        return DB::transaction(function () use ($data, $itens, $areaIds, $niveisEsperados) {
            $competencia = Competencia::create($data);
            $this->syncItens($competencia, $itens);
            if (! empty($areaIds)) {
                $competencia->areas()->sync($this->buildPivot($areaIds, $niveisEsperados));
            }
            return $competencia->load('itens', 'areas');
        });
    }

    /**
     * @param array<string> $areaIds
     * @param array<string, int> $niveisEsperados  [area_id => nivel_esperado]
     */
    public function update(Competencia $competencia, array $data, array $itens = [], array $areaIds = [], array $niveisEsperados = []): Competencia
    {
        return DB::transaction(function () use ($competencia, $data, $itens, $areaIds, $niveisEsperados) {
            $competencia->update($data);
            $this->syncItens($competencia, $itens);
            $competencia->areas()->sync($this->buildPivot($areaIds, $niveisEsperados));
            return $competencia->fresh()->load('itens', 'areas');
        });
    }

    /** Monta o array pivot [area_id => ['nivel_esperado' => N]] para sync(). */
    private function buildPivot(array $areaIds, array $niveisEsperados): array
    {
        $pivot = [];
        foreach ($areaIds as $id) {
            $nivel = (int) ($niveisEsperados[$id] ?? 3);
            $pivot[$id] = ['nivel_esperado' => max(1, min(5, $nivel))];
        }
        return $pivot;
    }

    public function delete(Competencia $competencia): void
    {
        if ($competencia->avaliacoes()->exists()) {
            throw new \RuntimeException('Não é possível excluir uma competência com avaliações vinculadas.');
        }
        $competencia->delete();
    }

    private function syncItens(Competencia $competencia, array $itens): void
    {
        $competencia->itens()->delete();
        foreach ($itens as $index => $item) {
            $descricao = trim($item['descricao'] ?? '');
            if ($descricao !== '') {
                ItemAvaliacao::create([
                    'competencia_id' => $competencia->id,
                    'descricao'      => $descricao,
                    'ordem'          => $index + 1,
                    'ativo'          => true,
                ]);
            }
        }
    }
}
