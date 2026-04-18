<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition(): array
    {
        return [
            'nome'        => $this->faker->unique()->words(3, true),
            'descricao'   => $this->faker->sentence(),
            'responsavel' => $this->faker->name(),
            'parent_id'   => null,
        ];
    }

    public function subarea(Area $pai): static
    {
        return $this->state(['parent_id' => $pai->id]);
    }
}
