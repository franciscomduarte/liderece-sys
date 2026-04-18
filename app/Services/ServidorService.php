<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Servidor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServidorService
{
    public function store(array $data): Servidor
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['nome'],
                'email'    => $data['email'],
                'password' => Hash::make(Str::random(16)),
            ]);

            return Servidor::create([
                'user_id'         => $user->id,
                'nome'            => $data['nome'],
                'email'           => $data['email'],
                'matricula'       => $data['matricula'],
                'cargo'           => $data['cargo'],
                'area_id'         => $data['area_id'],
                'perfil'          => $data['perfil'],
                'status'          => $data['status'] ?? 'ativo',
                'primeiro_acesso' => true,
            ]);
        });
    }

    public function update(Servidor $servidor, array $data): Servidor
    {
        return DB::transaction(function () use ($servidor, $data) {
            $servidor->update([
                'nome'      => $data['nome'],
                'matricula' => $data['matricula'],
                'cargo'     => $data['cargo'],
                'area_id'   => $data['area_id'],
                'perfil'    => $data['perfil'],
                'status'    => $data['status'],
            ]);

            $servidor->user->update(['name' => $data['nome']]);

            if ($servidor->email !== $data['email']) {
                $servidor->user->update(['email' => $data['email']]);
                $servidor->update(['email' => $data['email']]);
            }

            return $servidor->fresh();
        });
    }

    public function delete(Servidor $servidor): void
    {
        DB::transaction(function () use ($servidor) {
            $user = $servidor->user;
            $servidor->delete();
            $user?->delete();
        });
    }

    public function resetSenha(Servidor $servidor): string
    {
        $novaSenha = 'Sgc@' . Str::random(8);
        $servidor->user->update(['password' => Hash::make($novaSenha)]);
        $servidor->update(['primeiro_acesso' => true]);
        return $novaSenha;
    }
}
