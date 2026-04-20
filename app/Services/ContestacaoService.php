<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Contestacao;
use App\Models\Servidor;

class ContestacaoService
{
    public function podeContestar(Avaliacao $avaliacao, Servidor $servidor): bool
    {
        if ($avaliacao->tipo === 'autoavaliacao') return false;
        if (! $avaliacao->isEnviada()) return false;
        if ($avaliacao->servidor_id !== $servidor->id) return false;
        if ($avaliacao->contestacao()->exists()) return false;

        $ciclo = $avaliacao->ciclo;
        if (! $ciclo) return false;

        $prazo = $avaliacao->enviada_at->copy()->addDays($ciclo->prazo_contestacao_dias);

        return now()->lte($prazo);
    }

    public function contestar(Avaliacao $avaliacao, Servidor $servidor, string $justificativa): Contestacao
    {
        if (! $this->podeContestar($avaliacao, $servidor)) {
            throw new \RuntimeException('Não é possível contestar esta avaliação.');
        }

        $ciclo = $avaliacao->ciclo;
        $prazoResposta = now()->addDays($ciclo->prazo_contestacao_dias)->toDateString();

        $contestacao = Contestacao::create([
            'avaliacao_id'   => $avaliacao->id,
            'servidor_id'    => $servidor->id,
            'justificativa'  => $justificativa,
            'status'         => 'pendente',
            'prazo_resposta' => $prazoResposta,
        ]);

        app(NotificacaoService::class)->contestacaoEnviada($contestacao);

        return $contestacao;
    }

    public function responder(Contestacao $contestacao, string $resposta): void
    {
        if (! $contestacao->isPendente()) {
            throw new \RuntimeException('Esta contestação já foi respondida ou encerrada.');
        }

        $contestacao->update([
            'resposta_gestor' => $resposta,
            'status'          => 'respondida',
            'respondida_at'   => now(),
        ]);

        app(NotificacaoService::class)->contestacaoRespondida($contestacao);
    }

    public function encerrarVencidas(): int
    {
        return Contestacao::where('status', 'pendente')
            ->where('prazo_resposta', '<', now()->toDateString())
            ->update(['status' => 'encerrada']);
    }
}
