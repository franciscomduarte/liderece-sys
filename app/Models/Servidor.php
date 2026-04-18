<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servidor extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'servidores';

    protected $fillable = [
        'user_id', 'matricula', 'nome', 'email', 'cargo',
        'area_id', 'perfil', 'status', 'primeiro_acesso',
    ];

    protected $casts = [
        'primeiro_acesso' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'servidor_id');
    }

    public function avaliacoesRealizadas(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'avaliador_id');
    }

    public function contestacoes(): HasMany
    {
        return $this->hasMany(Contestacao::class, 'servidor_id');
    }

    public function notificacoes(): HasMany
    {
        return $this->hasMany(Notificacao::class, 'destinatario_id');
    }

    public function isAdmin(): bool
    {
        return $this->perfil === 'admin';
    }

    public function isGestor(): bool
    {
        return $this->perfil === 'gestor';
    }

    public function isServidor(): bool
    {
        return $this->perfil === 'servidor';
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeDaArea($query, string $areaId)
    {
        return $query->where('area_id', $areaId);
    }
}
