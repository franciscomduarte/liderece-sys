<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Notificacao;

class NotificacaoService
{
    public function avaliacaoEnviada(Avaliacao $avaliacao): void
    {
        if ($avaliacao->tipo !== 'area') {
            return;
        }

        $avaliacao->loadMissing(['servidor', 'competencia']);

        Notificacao::create([
            'destinatario_id' => $avaliacao->servidor_id,
            'tipo'            => 'avaliacao_enviada',
            'titulo'          => 'Avaliacao enviada pelo gestor',
            'descricao'       => 'Sua avaliacao da competencia "' . $avaliacao->competencia->nome . '" foi concluida e esta disponivel.',
            'link'            => route('servidor.avaliacoes.resultado', $avaliacao),
        ]);
    }
}
