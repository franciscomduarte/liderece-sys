<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAvaliacao extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'itens_avaliacao';

    protected $fillable = ['competencia_id', 'descricao', 'ordem', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }
}
