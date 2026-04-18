<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contestacao extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'contestacoes';

    protected $fillable = [
        'avaliacao_id', 'servidor_id', 'justificativa',
        'resposta_gestor', 'status', 'prazo_resposta', 'respondida_at',
    ];

    protected $casts = [
        'prazo_resposta' => 'date',
        'respondida_at'  => 'datetime',
    ];

    public function avaliacao(): BelongsTo
    {
        return $this->belongsTo(Avaliacao::class, 'avaliacao_id');
    }

    public function servidor(): BelongsTo
    {
        return $this->belongsTo(Servidor::class, 'servidor_id');
    }

    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    public function dentroDoProzo(): bool
    {
        return $this->prazo_resposta !== null && now()->lte($this->prazo_resposta);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }
}
