<?php

declare(strict_types=1);

namespace App\Livewire\Servidor\Historico;

use App\Services\RelatorioService;
use Livewire\Component;

class Index extends Component
{
    public function render(RelatorioService $service)
    {
        $servidor = auth()->user()->servidor;
        $historico = $service->historicoServidor($servidor);

        return view('livewire.servidor.historico.index', compact('historico'))
            ->layout('layouts.app')
            ->title('Histórico');
    }
}
