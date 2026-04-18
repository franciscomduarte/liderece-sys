<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePerfil
{
    public function handle(Request $request, Closure $next, string $perfil): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $servidor = Auth::user()->servidor;

        if (! $servidor) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Perfil não configurado.');
        }

        if ($servidor->status === 'inativo') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Conta inativa.');
        }

        if ($servidor->primeiro_acesso && ! $request->routeIs('trocar-senha*')) {
            return redirect()->route('trocar-senha');
        }

        if ($servidor->perfil !== $perfil) {
            return redirect()->route('unauthorized');
        }

        return $next($request);
    }
}
