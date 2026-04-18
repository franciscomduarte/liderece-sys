<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use Illuminate\Support\Facades\DB;

class CompetenciaService
{
    public function store(array $data, array $itens = [], array $areaIds = []): Competencia
    {
        return DB::transaction(function () use ($data, $itens, $areaIds) {
            $competencia = Competencia::create($data);
            $this->syncItens($competencia, $itens);
            if (! empty($areaIds)) {
                $competencia->areas()->sync($areaIds);
            }
            return $competencia->load('itens', 'areas');
        });
    }

    public function update(Competencia $competencia, array $data, array $itens = [], array $areaIds = []): Competencia
    {
        return DB::transaction(function () use ($competencia, $data, $itens, $areaIds) {
            $competencia->update($data);
            $this->syncItens($competencia, $itens);
            $competencia->areas()->sync($areaIds);
            return $competencia->fresh()->load('itens', 'areas');
        });
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
