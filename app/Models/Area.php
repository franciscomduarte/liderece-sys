<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'areas';

    protected $fillable = ['nome', 'descricao', 'responsavel', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Area::class, 'parent_id')->orderBy('nome');
    }

    public function servidores(): HasMany
    {
        return $this->hasMany(Servidor::class, 'area_id');
    }

    public function competencias(): BelongsToMany
    {
        return $this->belongsToMany(Competencia::class, 'competencias_areas', 'area_id', 'competencia_id');
    }

    public function isRaiz(): bool
    {
        return $this->parent_id === null;
    }
}
