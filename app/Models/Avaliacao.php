<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\AvaliacaoService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avaliacao extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'ciclo_id', 'servidor_id', 'avaliador_id', 'competencia_id',
        'tipo', 'media', 'comentario_gestor', 'status', 'enviada_at',
    ];

    protected $casts = [
        'media'      => 'float',
        'enviada_at' => 'datetime',
    ];

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(Ciclo::class, 'ciclo_id');
    }

    public function servidor(): BelongsTo
    {
        return $this->belongsTo(Servidor::class, 'servidor_id');
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(Servidor::class, 'avaliador_id');
    }

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(RespostaAvaliacao::class, 'avaliacao_id');
    }

    public function isEnviada(): bool
    {
        return $this->status === 'enviada';
    }

    public function scopeEnviadas($query)
    {
        return $query->where('status', 'enviada');
    }

    public function scopeRascunho($query)
    {
        return $query->where('status', 'rascunho');
    }

    public function getNivelProficienciaAttribute(): ?int
    {
        if ($this->media === null) {
            return null;
        }
        return AvaliacaoService::nivelProficiencia($this->media);
    }
}
