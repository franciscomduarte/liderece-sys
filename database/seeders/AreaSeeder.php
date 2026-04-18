<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nome' => 'Tecnologia da Informação', 'descricao' => 'Área responsável pela infraestrutura e sistemas de TI', 'responsavel' => 'Carlos Mendes'],
            ['nome' => 'Recursos Humanos',         'descricao' => 'Área de gestão de pessoas e desenvolvimento organizacional', 'responsavel' => 'Mariana Costa'],
            ['nome' => 'Financeiro',               'descricao' => 'Área responsável pela gestão financeira e orçamentária', 'responsavel' => 'Roberto Lima'],
            ['nome' => 'Jurídico',                 'descricao' => 'Assessoria jurídica e consultoria legal', 'responsavel' => 'Dra. Patricia Souza'],
            ['nome' => 'Comunicação',              'descricao' => 'Área de comunicação institucional e relações públicas', 'responsavel' => 'Fernanda Alves'],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate(['nome' => $area['nome']], $area);
        }
    }
}
