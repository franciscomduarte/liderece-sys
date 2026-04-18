<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Contestacao;
use App\Models\User;

class ContestacaoPolicy
{
    public function view(User $user, Contestacao $contestacao): bool
    {
        $servidor = $user->servidor;
        if (! $servidor) return false;
        if ($servidor->isAdmin()) return true;
        if ($contestacao->servidor_id === $servidor->id) return true;

        if ($servidor->isGestor()) {
            return $contestacao->avaliacao->servidor->area_id === $servidor->area_id;
        }

        return false;
    }

    public function responder(User $user, Contestacao $contestacao): bool
    {
        $servidor = $user->servidor;
        if (! $servidor) return false;
        if (! $servidor->isGestor() && ! $servidor->isAdmin()) return false;
        if (! $contestacao->isPendente()) return false;

        if ($servidor->isGestor()) {
            return $contestacao->avaliacao->servidor->area_id === $servidor->area_id;
        }

        return true;
    }
}
