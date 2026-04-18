<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasUuids;

    protected $table = 'notificacoes';

    protected $fillable = [
        'destinatario_id', 'tipo', 'titulo', 'descricao', 'lida', 'link',
    ];

    protected $casts = ['lida' => 'boolean'];

    public function destinatario(): BelongsTo
    {
        return $this->belongsTo(Servidor::class, 'destinatario_id');
    }

    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }
}
