<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Area;

class AreaService
{
    public function store(array $data): Area
    {
        return Area::create($data);
    }

    public function update(Area $area, array $data): Area
    {
        $area->update($data);
        return $area->fresh();
    }

    public function delete(Area $area): void
    {
        if ($area->servidores()->exists()) {
            throw new \RuntimeException('Não é possível excluir uma área com servidores vinculados.');
        }

        if ($area->children()->exists()) {
            throw new \RuntimeException('Não é possível excluir uma área que possui subáreas vinculadas.');
        }

        $area->delete();
    }
}
