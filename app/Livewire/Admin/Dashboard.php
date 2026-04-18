<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'servidores' => Servidor::where('status', 'ativo')->count(),
            'areas'      => Area::count(),
            'competencias' => Competencia::where('ativo', true)->count(),
            'ciclo_ativo'  => Ciclo::cicloAtivo()?->nome ?? null,
        ];

        return view('livewire.admin.dashboard', compact('stats'))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
