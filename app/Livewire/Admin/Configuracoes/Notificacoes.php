<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracoes;

use App\Models\Configuracao;
use Livewire\Component;

class Notificacoes extends Component
{
    public bool $notif_avaliacao_pendente = true;
    public bool $notif_nova_avaliacao     = true;
    public bool $notif_relatorio_mensal   = false;

    public function mount(): void
    {
        $config = Configuracao::get();
        $this->notif_avaliacao_pendente = $config->notif_avaliacao_pendente;
        $this->notif_nova_avaliacao     = $config->notif_nova_avaliacao;
        $this->notif_relatorio_mensal   = $config->notif_relatorio_mensal;
    }

    public function salvar(): void
    {
        Configuracao::get()->update([
            'notif_avaliacao_pendente' => $this->notif_avaliacao_pendente,
            'notif_nova_avaliacao'     => $this->notif_nova_avaliacao,
            'notif_relatorio_mensal'   => $this->notif_relatorio_mensal,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Configurações de notificações salvas!');
    }

    public function render()
    {
        return view('livewire.admin.configuracoes.notificacoes')
            ->layout('layouts.app')
            ->title('Notificações');
    }
}
