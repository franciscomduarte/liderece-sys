<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirecionarPorPerfil();
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Credenciais inválidas.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $servidor = Auth::user()->servidor;

        if (! $servidor) {
            Auth::logout();
            return back()->with('error', 'Usuário não possui perfil de servidor configurado.');
        }

        if ($servidor->status === 'inativo') {
            Auth::logout();
            return back()->with('error', 'Sua conta está inativa. Contate o administrador.');
        }

        if ($servidor->primeiro_acesso) {
            return redirect()->route('trocar-senha');
        }

        return $this->redirecionarPorPerfil();
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirecionarPorPerfil(): RedirectResponse
    {
        $servidor = Auth::user()?->servidor;

        if (! $servidor) {
            return redirect()->route('login');
        }

        return match ($servidor->perfil) {
            'admin'   => redirect()->route('admin.dashboard'),
            'gestor'  => redirect()->route('gestor.dashboard'),
            default   => redirect()->route('servidor.dashboard'),
        };
    }
}
