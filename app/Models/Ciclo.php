<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciclo extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'ciclos';

    protected $fillable = [
        'nome', 'data_inicio', 'data_fim', 'status', 'created_by',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim'    => 'date',
    ];

    public function criador(): BelongsTo
    {
        return $this->belongsTo(Servidor::class, 'created_by');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'ciclo_id');
    }

    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }

    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }

    public static function cicloAtivo(): ?self
    {
        return static::where('status', 'ativo')->first();
    }
}
