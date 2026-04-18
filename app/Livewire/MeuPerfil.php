<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class MeuPerfil extends Component
{
    public string $senhaAtual    = '';
    public string $novaSenha     = '';
    public string $confirmaSenha = '';

    public function salvarSenha(): void
    {
        $this->validate([
            'senhaAtual'    => ['required'],
            'novaSenha'     => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'confirmaSenha' => ['required'],
        ], [
            'novaSenha.confirmed'    => 'A confirmação da senha não coincide.',
            'novaSenha.min'          => 'A senha deve ter no mínimo 8 caracteres.',
            'novaSenha.letters'      => 'A senha deve conter letras.',
            'novaSenha.numbers'      => 'A senha deve conter números.',
        ]);

        $user = auth()->user();

        if (! Hash::check($this->senhaAtual, $user->password)) {
            $this->addError('senhaAtual', 'Senha atual incorreta.');
            return;
        }

        $user->update(['password' => Hash::make($this->novaSenha)]);

        $this->reset('senhaAtual', 'novaSenha', 'confirmaSenha');

        $this->dispatch('toast', type: 'success', message: 'Senha alterada com sucesso!');
    }

    public function render()
    {
        $servidor = auth()->user()->servidor;

        return view('livewire.meu-perfil', compact('servidor'))
            ->layout('layouts.app')
            ->title('Meu Perfil');
    }
}
