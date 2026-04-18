<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Avaliacao;
use App\Models\Contestacao;
use App\Models\Servidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContestacaoFactory extends Factory
{
    protected $model = Contestacao::class;

    public function definition(): array
    {
        $avaliacao = Avaliacao::factory()->enviada()->create();

        return [
            'avaliacao_id'   => $avaliacao->id,
            'servidor_id'    => $avaliacao->servidor_id,
            'justificativa'  => $this->faker->paragraph(),
            'resposta_gestor' => null,
            'status'         => 'pendente',
            'prazo_resposta' => now()->addDays(5)->toDateString(),
        ];
    }

    public function respondida(): static
    {
        return $this->state([
            'status'          => 'respondida',
            'resposta_gestor' => $this->faker->paragraph(),
            'respondida_at'   => now(),
        ]);
    }

    public function encerrada(): static
    {
        return $this->state([
            'status'         => 'encerrada',
            'prazo_resposta' => now()->subDays(1)->toDateString(),
        ]);
    }
}
