<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracoes;

use App\Models\Configuracao;
use Livewire\Component;

class Geral extends Component
{
    public string $nome_organizacao = '';
    public int    $escala_maxima    = 5;

    public function mount(): void
    {
        $config = Configuracao::get();
        $this->nome_organizacao = $config->nome_organizacao;
        $this->escala_maxima    = $config->escala_maxima;
    }

    protected function rules(): array
    {
        return [
            'nome_organizacao' => 'required|string|max:150',
            'escala_maxima'    => 'required|integer|min:3|max:10',
        ];
    }

    protected array $messages = [
        'nome_organizacao.required' => 'O nome da organização é obrigatório.',
        'nome_organizacao.max'      => 'O nome pode ter no máximo 150 caracteres.',
        'escala_maxima.min'         => 'A escala mínima é 3 pontos.',
        'escala_maxima.max'         => 'A escala máxima é 10 pontos.',
    ];

    public function salvar(): void
    {
        $this->validate();

        Configuracao::get()->update([
            'nome_organizacao' => $this->nome_organizacao,
            'escala_maxima'    => $this->escala_maxima,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Configurações salvas com sucesso!');
    }

    public function render()
    {
        return view('livewire.admin.configuracoes.geral')
            ->layout('layouts.app')
            ->title('Configurações Gerais');
    }
}
