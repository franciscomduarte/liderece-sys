<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\RespostaAvaliacao;
use App\Models\Servidor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AvaliacaoService
{
    public function obterOuCriar(
        Ciclo $ciclo,
        Servidor $servidor,
        Servidor $avaliador,
        Competencia $competencia,
        string $tipo
    ): Avaliacao {
        return Avaliacao::firstOrCreate(
            [
                'ciclo_id'       => $ciclo->id,
                'servidor_id'    => $servidor->id,
                'avaliador_id'   => $avaliador->id,
                'competencia_id' => $competencia->id,
                'tipo'           => $tipo,
            ],
            ['status' => 'rascunho']
        );
    }

    public function salvarRespostas(Avaliacao $avaliacao, array $notas): void
    {
        if ($avaliacao->isEnviada()) {
            throw new \RuntimeException('Avaliação enviada não pode ser alterada.');
        }

        DB::transaction(function () use ($avaliacao, $notas) {
            foreach ($notas as $itemId => $nota) {
                if ($nota === null || $nota === '') {
                    RespostaAvaliacao::where('avaliacao_id', $avaliacao->id)
                        ->where('item_id', $itemId)
                        ->delete();
                    continue;
                }
                RespostaAvaliacao::updateOrCreate(
                    ['avaliacao_id' => $avaliacao->id, 'item_id' => $itemId],
                    ['nota' => (int) $nota]
                );
            }
        });
    }

    public function enviar(Avaliacao $avaliacao, array $notas): void
    {
        if ($avaliacao->isEnviada()) {
            throw new \RuntimeException('Avaliação já foi enviada.');
        }

        $totalItens = $avaliacao->competencia->itensAtivos()->count();
        $notasPreenchidas = collect($notas)->filter(fn($n) => $n !== null && $n !== '');

        if ($notasPreenchidas->count() < $totalItens) {
            throw new \RuntimeException('Todos os itens devem ser avaliados antes de enviar.');
        }

        DB::transaction(function () use ($avaliacao, $notas) {
            $this->salvarRespostas($avaliacao, $notas);
            $avaliacao->refresh();

            $avaliacao->update([
                'media'      => $this->calcularMedia($avaliacao),
                'status'     => 'enviada',
                'enviada_at' => now(),
            ]);
        });

        app(NotificacaoService::class)->avaliacaoEnviada($avaliacao->refresh());
    }

    public function calcularMedia(Avaliacao $avaliacao): float
    {
        $notas = $avaliacao->respostas()->pluck('nota');
        if ($notas->isEmpty()) {
            return 0.0;
        }
        return round($notas->avg(), 1);
    }

    public function salvarComentarioGestor(Avaliacao $avaliacao, string $comentario): void
    {
        if ($avaliacao->isEnviada()) {
            throw new \RuntimeException('Avaliação enviada não pode ser alterada.');
        }
        $avaliacao->update(['comentario_gestor' => $comentario]);
    }

    public function reabrir(Avaliacao $avaliacao): void
    {
        if (! $avaliacao->isEnviada()) {
            throw new \RuntimeException('Apenas avaliações enviadas podem ser reabertas.');
        }

        $avaliacao->update([
            'status'     => 'rascunho',
            'media'      => null,
            'enviada_at' => null,
        ]);
    }

    public function competenciasDoServidor(Ciclo $ciclo, Servidor $servidor): Collection
    {
        return Competencia::ativas()
            ->whereHas('areas', fn($q) => $q->where('areas.id', $servidor->area_id))
            ->with('itensAtivos')
            ->orderBy('nome')
            ->get();
    }

    public function avaliacoesIndexadas(Ciclo $ciclo, Servidor $servidor, string $tipo): array
    {
        return Avaliacao::where('ciclo_id', $ciclo->id)
            ->where('servidor_id', $servidor->id)
            ->where('tipo', $tipo)
            ->get()
            ->keyBy('competencia_id')
            ->all();
    }
}
