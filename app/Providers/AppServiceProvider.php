<?php

namespace App\Providers;

use App\Models\Avaliacao;
use App\Policies\AvaliacaoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Avaliacao::class => AvaliacaoPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->registerPolicies();
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
