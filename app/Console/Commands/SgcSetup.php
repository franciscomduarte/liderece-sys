<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Configuracao;
use App\Models\Servidor;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SgcSetup extends Command
{
    protected $signature = 'sgc:setup
                            {--name= : Nome do administrador}
                            {--email= : E-mail do administrador}
                            {--password= : Senha inicial do administrador}
                            {--matricula= : Matrícula do administrador}
                            {--cargo= : Cargo do administrador}';

    protected $description = 'Configura o SGC: cria o administrador inicial e as configurações padrão';

    public function handle(): int
    {
        $this->info('Configurando o SGC...');

        $nome      = $this->option('name')      ?? $this->ask('Nome completo do administrador');
        $email     = $this->option('email')     ?? $this->ask('E-mail do administrador');
        $matricula = $this->option('matricula') ?? $this->ask('Matrícula funcional');
        $cargo     = $this->option('cargo')     ?? $this->ask('Cargo');
        $senha     = $this->option('password')  ?? $this->secret('Senha inicial (mínimo 8 caracteres)');

        if (strlen((string) $senha) < 8) {
            $this->error('A senha deve ter no mínimo 8 caracteres.');
            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("O e-mail {$email} já está cadastrado.");
            return self::FAILURE;
        }

        DB::transaction(function () use ($nome, $email, $matricula, $cargo, $senha) {
            $user = User::create([
                'name'     => $nome,
                'email'    => $email,
                'password' => Hash::make($senha),
            ]);

            Servidor::create([
                'user_id'        => $user->id,
                'matricula'      => $matricula,
                'nome'           => $nome,
                'email'          => $email,
                'cargo'          => $cargo,
                'perfil'         => 'admin',
                'status'         => 'ativo',
                'primeiro_acesso' => false,
            ]);

            Configuracao::get();
        });

        $this->info('');
        $this->info('✓ Administrador criado com sucesso!');
        $this->table(['Campo', 'Valor'], [
            ['Nome', $nome],
            ['E-mail', $email],
            ['Matrícula', $matricula],
            ['Perfil', 'Administrador'],
        ]);
        $this->info('');
        $this->warn('Acesse o sistema e configure as áreas, competências e servidores.');

        return self::SUCCESS;
    }
}
