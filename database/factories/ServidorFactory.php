<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Area;
use App\Models\Servidor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServidorFactory extends Factory
{
    protected $model = Servidor::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id'         => $user->id,
            'matricula'       => $this->faker->unique()->numerify('######'),
            'nome'            => $this->faker->name(),
            'email'           => $user->email,
            'cargo'           => $this->faker->jobTitle(),
            'area_id'         => Area::factory(),
            'perfil'          => 'servidor',
            'status'          => 'ativo',
            'primeiro_acesso' => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(['perfil' => 'admin', 'area_id' => null]);
    }

    public function gestor(): static
    {
        return $this->state(['perfil' => 'gestor']);
    }

    public function servidor(): static
    {
        return $this->state(['perfil' => 'servidor']);
    }

    public function inativo(): static
    {
        return $this->state(['status' => 'inativo']);
    }

    public function primeiroAcesso(): static
    {
        return $this->state(['primeiro_acesso' => true]);
    }
}
