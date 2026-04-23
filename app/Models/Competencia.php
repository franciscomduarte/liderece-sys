<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competencia extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'competencias';

    protected $fillable = ['nome', 'descricao', 'tipo', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function itens(): HasMany
    {
        return $this->hasMany(ItemAvaliacao::class, 'competencia_id')->orderBy('ordem');
    }

    public function itensAtivos(): HasMany
    {
        return $this->hasMany(ItemAvaliacao::class, 'competencia_id')
            ->where('ativo', true)
            ->orderBy('ordem');
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'competencias_areas', 'competencia_id', 'area_id')
            ->withPivot('nivel_esperado');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'competencia_id');
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }
}
