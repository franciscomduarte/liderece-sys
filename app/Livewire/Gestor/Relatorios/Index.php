<?php

declare(strict_types=1);

namespace App\Livewire\Gestor\Relatorios;

use App\Models\Ciclo;
use App\Services\RelatorioService;
use Livewire\Component;

class Index extends Component
{
    public string $cicloId = '';

    public function mount(): void
    {
        $ativo = Ciclo::cicloAtivo();
        $this->cicloId = $ativo?->id ?? Ciclo::latest()->first()?->id ?? '';
    }

    public function render(RelatorioService $service)
    {
        $gestor = auth()->user()->servidor;
        $ciclos = Ciclo::orderByDesc('created_at')->get();
        $ciclo  = $this->cicloId ? Ciclo::find($this->cicloId) : null;

        $resumo    = ($ciclo && $gestor->area) ? $service->resumoArea($ciclo, $gestor->area) : null;
        $servidores = ($ciclo && $gestor->area) ? $service->servidoresDaArea($ciclo, $gestor->area) : collect();

        return view('livewire.gestor.relatorios.index', compact(
            'ciclos', 'ciclo', 'resumo', 'servidores'
        ))
            ->layout('layouts.app')
            ->title('Relatórios');
    }
}
