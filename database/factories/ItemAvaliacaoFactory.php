<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemAvaliacaoFactory extends Factory
{
    protected $model = ItemAvaliacao::class;

    public function definition(): array
    {
        return [
            'competencia_id' => Competencia::factory(),
            'descricao'      => $this->faker->sentence(8),
            'ordem'          => $this->faker->numberBetween(1, 10),
            'ativo'          => true,
        ];
    }
}
