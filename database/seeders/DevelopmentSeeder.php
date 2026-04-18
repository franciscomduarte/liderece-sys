<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'development', 'testing')) {
            throw new \Exception('DevelopmentSeeder só roda em ambiente local/development/testing.');
        }

        $this->call([
            ConfiguracaoSeeder::class,
            AreaSeeder::class,
            ServidorSeeder::class,
            CompetenciaSeeder::class,
            CicloSeeder::class,
        ]);
    }
}
