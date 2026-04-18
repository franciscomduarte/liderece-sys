<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ciclo;
use App\Models\Servidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CicloFactory extends Factory
{
    protected $model = Ciclo::class;

    public function definition(): array
    {
        $inicio = $this->faker->dateTimeBetween('-1 year', '+1 year');
        $fim    = (clone $inicio)->modify('+6 months');

        return [
            'nome'                   => 'Ciclo ' . $this->faker->year() . '/' . $this->faker->numberBetween(1, 2),
            'data_inicio'            => $inicio->format('Y-m-d'),
            'data_fim'               => $fim->format('Y-m-d'),
            'status'                 => 'inativo',
            'prazo_contestacao_dias' => 10,
            'created_by'             => Servidor::factory()->admin(),
        ];
    }

    public function ativo(): static
    {
        return $this->state(['status' => 'ativo']);
    }
}
