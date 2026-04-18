<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ciclo;
use App\Models\Servidor;

class CicloService
{
    public function store(array $data, Servidor $criador): Ciclo
    {
        if (Ciclo::cicloAtivo()) {
            throw new \RuntimeException('Já existe um ciclo ativo. Desative-o antes de criar um novo.');
        }

        return Ciclo::create([
            'nome'                   => $data['nome'],
            'data_inicio'            => $data['data_inicio'],
            'data_fim'               => $data['data_fim'],
            'prazo_contestacao_dias' => $data['prazo_contestacao_dias'],
            'status'                 => 'inativo',
            'created_by'             => $criador->id,
        ]);
    }

    public function ativar(Ciclo $ciclo): void
    {
        if (Ciclo::cicloAtivo()) {
            throw new \RuntimeException('Já existe um ciclo ativo. Desative-o primeiro.');
        }
        $ciclo->update(['status' => 'ativo']);
    }

    public function desativar(Ciclo $ciclo): void
    {
        $ciclo->update(['status' => 'inativo']);
    }

    public function delete(Ciclo $ciclo): void
    {
        if ($ciclo->isAtivo()) {
            throw new \RuntimeException('Não é possível excluir um ciclo ativo.');
        }
        if ($ciclo->avaliacoes()->exists()) {
            throw new \RuntimeException('Não é possível excluir um ciclo com avaliações vinculadas.');
        }
        $ciclo->delete();
    }
}
