<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracoes;

use App\Models\Avaliacao;
use App\Models\Competencia;
use App\Models\Servidor;
use Livewire\Component;

class Dados extends Component
{
    public function render()
    {
        $stats = [
            'servidores'  => Servidor::count(),
            'competencias'=> Competencia::count(),
            'avaliacoes'  => Avaliacao::count(),
        ];

        return view('livewire.admin.configuracoes.dados', compact('stats'))
            ->layout('layouts.app')
            ->title('Dados');
    }
}
