<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracoes;

use App\Models\Configuracao;
use Livewire\Component;

class Seguranca extends Component
{
    public bool $auth_dois_fatores      = false;
    public int  $sessao_expira_minutos  = 30;

    public function mount(): void
    {
        $config = Configuracao::get();
        $this->auth_dois_fatores     = $config->auth_dois_fatores;
        $this->sessao_expira_minutos = $config->sessao_expira_minutos;
    }

    protected function rules(): array
    {
        return [
            'sessao_expira_minutos' => 'required|integer|min:5|max:480',
        ];
    }

    protected array $messages = [
        'sessao_expira_minutos.min' => 'O tempo mínimo de sessão é 5 minutos.',
        'sessao_expira_minutos.max' => 'O tempo máximo de sessão é 480 minutos (8 horas).',
    ];

    public function salvar(): void
    {
        $this->validate();

        Configuracao::get()->update([
            'auth_dois_fatores'     => $this->auth_dois_fatores,
            'sessao_expira_minutos' => $this->sessao_expira_minutos,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Configurações de segurança salvas!');
    }

    public function render()
    {
        return view('livewire.admin.configuracoes.seguranca')
            ->layout('layouts.app')
            ->title('Segurança');
    }
}
