<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Servidor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServidorSeeder extends Seeder
{
    public function run(): void
    {
        $areaTI   = Area::where('nome', 'Tecnologia da Informação')->first();
        $areaRH   = Area::where('nome', 'Recursos Humanos')->first();
        $areaComm = Area::where('nome', 'Comunicação')->first();

        $senha = Hash::make('Senha@123');

        $servidores = [
            // Admin
            [
                'user' => ['name' => 'Administrador SGC', 'email' => 'admin@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0001', 'nome' => 'Administrador SGC', 'email' => 'admin@sgc.gov.br', 'cargo' => 'Administrador do Sistema', 'area_id' => null, 'perfil' => 'admin', 'primeiro_acesso' => false],
            ],
            // Gestor
            [
                'user' => ['name' => 'Carlos Mendes', 'email' => 'gestor@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0002', 'nome' => 'Carlos Mendes', 'email' => 'gestor@sgc.gov.br', 'cargo' => 'Gerente de TI', 'area_id' => $areaTI?->id, 'perfil' => 'gestor', 'primeiro_acesso' => false],
            ],
            // Servidores
            [
                'user' => ['name' => 'Ana Clara Silva', 'email' => 'ana@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0003', 'nome' => 'Ana Clara Silva', 'email' => 'ana@sgc.gov.br', 'cargo' => 'Analista de Sistemas', 'area_id' => $areaTI?->id, 'perfil' => 'servidor', 'primeiro_acesso' => false],
            ],
            [
                'user' => ['name' => 'Bruno Costa', 'email' => 'bruno@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0004', 'nome' => 'Bruno Costa', 'email' => 'bruno@sgc.gov.br', 'cargo' => 'Desenvolvedor', 'area_id' => $areaTI?->id, 'perfil' => 'servidor', 'primeiro_acesso' => false],
            ],
            [
                'user' => ['name' => 'Daniela Rodrigues', 'email' => 'daniela@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0005', 'nome' => 'Daniela Rodrigues', 'email' => 'daniela@sgc.gov.br', 'cargo' => 'Analista de RH', 'area_id' => $areaRH?->id, 'perfil' => 'servidor', 'primeiro_acesso' => false],
            ],
            [
                'user' => ['name' => 'Eduardo Ferreira', 'email' => 'eduardo@sgc.gov.br', 'password' => $senha],
                'servidor' => ['matricula' => '0006', 'nome' => 'Eduardo Ferreira', 'email' => 'eduardo@sgc.gov.br', 'cargo' => 'Assessor de Comunicação', 'area_id' => $areaComm?->id, 'perfil' => 'servidor', 'primeiro_acesso' => false],
            ],
        ];

        foreach ($servidores as $data) {
            DB::transaction(function () use ($data) {
                if (User::where('email', $data['user']['email'])->exists()) {
                    return;
                }

                $user = User::create($data['user']);
                Servidor::create(array_merge($data['servidor'], ['user_id' => $user->id, 'status' => 'ativo']));
            });
        }
    }
}
