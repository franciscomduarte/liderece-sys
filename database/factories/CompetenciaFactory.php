<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Competencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetenciaFactory extends Factory
{
    protected $model = Competencia::class;

    public function definition(): array
    {
        return [
            'nome'      => $this->faker->unique()->words(rand(2, 4), true),
            'descricao' => $this->faker->sentence(),
            'tipo'      => $this->faker->randomElement(['comportamental', 'técnica', 'gerencial']),
            'ativo'     => true,
        ];
    }

    public function inativa(): static
    {
        return $this->state(['ativo' => false]);
    }
}
