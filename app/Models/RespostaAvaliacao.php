<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespostaAvaliacao extends Model
{
    use HasUuids;

    protected $table = 'respostas_avaliacao';

    protected $fillable = ['avaliacao_id', 'item_id', 'nota'];

    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(Avaliacao::class, 'avaliacao_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemAvaliacao::class, 'item_id');
    }
}
