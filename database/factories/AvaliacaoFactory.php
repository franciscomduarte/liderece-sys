<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvaliacaoFactory extends Factory
{
    protected $model = Avaliacao::class;

    public function definition(): array
    {
        $servidor = Servidor::factory()->create();

        return [
            'ciclo_id'       => Ciclo::factory(),
            'servidor_id'    => $servidor->id,
            'avaliador_id'   => $servidor->id,
            'competencia_id' => Competencia::factory(),
            'tipo'           => 'autoavaliacao',
            'media'          => null,
            'status'         => 'rascunho',
            'enviada_at'     => null,
        ];
    }

    public function enviada(): static
    {
        return $this->state([
            'status'     => 'enviada',
            'media'      => $this->faker->randomFloat(1, 1, 5),
            'enviada_at' => now(),
        ]);
    }

    public function area(): static
    {
        return $this->state(['tipo' => 'area']);
    }
}
