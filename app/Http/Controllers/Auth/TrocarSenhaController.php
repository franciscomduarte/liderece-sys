<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TrocarSenhaController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $servidor = Auth::user()?->servidor;

        if (! $servidor || ! $servidor->primeiro_acesso) {
            return redirect()->route('login');
        }

        return view('auth.trocar-senha');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não conferem.',
        ]);

        $user    = Auth::user();
        $servidor = $user->servidor;

        $user->update(['password' => Hash::make($request->password)]);
        $servidor->update(['primeiro_acesso' => false]);

        return match ($servidor->perfil) {
            'admin'  => redirect()->route('admin.dashboard'),
            'gestor' => redirect()->route('gestor.dashboard'),
            default  => redirect()->route('servidor.dashboard'),
        };
    }
}
