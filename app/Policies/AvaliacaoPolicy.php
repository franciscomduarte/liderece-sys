<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Avaliacao;
use App\Models\User;

class AvaliacaoPolicy
{
    public function view(User $user, Avaliacao $avaliacao): bool
    {
        $servidor = $user->servidor;
        if (! $servidor) return false;

        if ($servidor->isAdmin()) return true;

        if ($avaliacao->servidor_id === $servidor->id) return true;

        if ($servidor->isGestor()) {
            return $avaliacao->servidor->area_id === $servidor->area_id;
        }

        return false;
    }

    public function reabrir(User $user, Avaliacao $avaliacao): bool
    {
        $servidor = $user->servidor;
        return $servidor?->isAdmin() ?? false;
    }

    public function fill(User $user, Avaliacao $avaliacao): bool
    {
        if ($avaliacao->isEnviada()) return false;

        $servidor = $user->servidor;
        if (! $servidor) return false;

        if ($avaliacao->tipo === 'autoavaliacao') {
            return $avaliacao->avaliador_id === $servidor->id;
        }

        if ($avaliacao->tipo === 'area') {
            return $avaliacao->avaliador_id === $servidor->id && $servidor->isGestor();
        }

        return false;
    }
}
