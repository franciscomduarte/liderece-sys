<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Contestacao;
use App\Models\Notificacao;
use App\Models\Servidor;

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

    public function contestacaoEnviada(Contestacao $contestacao): void
    {
        $contestacao->loadMissing(['servidor.area', 'avaliacao.competencia']);

        $area = $contestacao->servidor->area;
        if (! $area) {
            return;
        }

        $gestores = Servidor::where('area_id', $area->id)
            ->where('perfil', 'gestor')
            ->where('status', 'ativo')
            ->get();

        foreach ($gestores as $gestor) {
            Notificacao::create([
                'destinatario_id' => $gestor->id,
                'tipo'            => 'contestacao_enviada',
                'titulo'          => 'Nova contestacao recebida',
                'descricao'       => $contestacao->servidor->nome . ' contestou a avaliacao de "' . $contestacao->avaliacao->competencia->nome . '".',
                'link'            => route('gestor.contestacoes'),
            ]);
        }
    }

    public function contestacaoRespondida(Contestacao $contestacao): void
    {
        $contestacao->loadMissing(['avaliacao.competencia']);

        Notificacao::create([
            'destinatario_id' => $contestacao->servidor_id,
            'tipo'            => 'contestacao_respondida',
            'titulo'          => 'Contestacao respondida',
            'descricao'       => 'O gestor respondeu sua contestacao da competencia "' . $contestacao->avaliacao->competencia->nome . '".',
            'link'            => route('servidor.avaliacoes.resultado', $contestacao->avaliacao),
        ]);
    }
}
