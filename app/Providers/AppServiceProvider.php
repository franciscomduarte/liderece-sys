<?php

namespace App\Providers;

use App\Models\Avaliacao;
use App\Models\Contestacao;
use App\Policies\AvaliacaoPolicy;
use App\Policies\ContestacaoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Avaliacao::class    => AvaliacaoPolicy::class,
        Contestacao::class  => ContestacaoPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
