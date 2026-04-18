<?php
declare(strict_types=1);
namespace App\Livewire\Gestor\Servidores;
use Livewire\Component;
class Perfil extends Component
{
    public function render()
    {
        return view('livewire.gestor.servidores.perfil')
            ->layout('layouts.app')
            ->title('Perfil do Servidor');
    }
}
