<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TrocarSenhaController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

// Autenticação
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/trocar-senha', [TrocarSenhaController::class, 'show'])->name('trocar-senha');
    Route::post('/trocar-senha', [TrocarSenhaController::class, 'store'])->name('trocar-senha.store');
    Route::get('/meu-perfil', App\Livewire\MeuPerfil::class)->name('meu-perfil');
});

// Rota raiz → redirecionar por perfil
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }
    $perfil = auth()->user()->servidor?->perfil;
    return match ($perfil) {
        'admin'  => redirect()->route('admin.dashboard'),
        'gestor' => redirect()->route('gestor.dashboard'),
        default  => redirect()->route('servidor.dashboard'),
    };
})->middleware('auth');

Route::get('/unauthorized', fn () => view('errors.unauthorized'))->name('unauthorized');

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'perfil:admin'])->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/servidores', App\Livewire\Admin\Servidores\Index::class)->name('admin.servidores');
    Route::get('/servidores/{servidor}', App\Livewire\Admin\Servidores\Perfil::class)->name('admin.servidores.perfil');
    Route::get('/areas', App\Livewire\Admin\Areas\Index::class)->name('admin.areas');
    Route::get('/competencias', App\Livewire\Admin\Competencias\Index::class)->name('admin.competencias');
    Route::get('/avaliacoes', App\Livewire\Admin\Avaliacoes\Index::class)->name('admin.avaliacoes');
    Route::get('/contestacoes', App\Livewire\Admin\Contestacoes\Index::class)->name('admin.contestacoes');
    Route::get('/relatorios', App\Livewire\Admin\Relatorios\Index::class)->name('admin.relatorios');
    Route::get('/relatorios/exportar/csv', [RelatorioController::class, 'adminCsv'])->name('admin.relatorios.exportar.csv');
    Route::get('/relatorios/exportar/pdf', [RelatorioController::class, 'adminPdf'])->name('admin.relatorios.exportar.pdf');

    Route::prefix('configuracoes')->group(function () {
        Route::redirect('/', '/admin/configuracoes/geral');
        Route::get('/geral', App\Livewire\Admin\Configuracoes\Geral::class)->name('admin.config.geral');
        Route::get('/ciclos', App\Livewire\Admin\Configuracoes\Ciclos::class)->name('admin.config.ciclos');
        Route::get('/notificacoes', App\Livewire\Admin\Configuracoes\Notificacoes::class)->name('admin.config.notificacoes');
        Route::get('/seguranca', App\Livewire\Admin\Configuracoes\Seguranca::class)->name('admin.config.seguranca');
        Route::get('/dados', App\Livewire\Admin\Configuracoes\Dados::class)->name('admin.config.dados');
    });
});

// ─── Gestor ───────────────────────────────────────────────────────────────────
Route::prefix('gestor')->middleware(['auth', 'perfil:gestor'])->group(function () {
    Route::get('/dashboard', App\Livewire\Gestor\Dashboard::class)->name('gestor.dashboard');
    Route::get('/avaliacoes', App\Livewire\Gestor\Avaliacoes\Index::class)->name('gestor.avaliacoes');
    Route::get('/avaliacoes/{avaliacao}/avaliar', App\Livewire\Gestor\Avaliacoes\Form::class)->name('gestor.avaliacoes.form');
    Route::get('/contestacoes', App\Livewire\Gestor\Contestacoes\Index::class)->name('gestor.contestacoes');
    Route::get('/relatorios', App\Livewire\Gestor\Relatorios\Index::class)->name('gestor.relatorios');
    Route::get('/relatorios/exportar/csv', [RelatorioController::class, 'gestorCsv'])->name('gestor.relatorios.exportar.csv');
    Route::get('/servidores/{servidor}', App\Livewire\Gestor\Servidores\Perfil::class)->name('gestor.servidores.perfil');
});

// ─── Servidor ─────────────────────────────────────────────────────────────────
Route::prefix('servidor')->middleware(['auth', 'perfil:servidor'])->group(function () {
    Route::get('/dashboard', App\Livewire\Servidor\Dashboard::class)->name('servidor.dashboard');
    Route::get('/avaliacoes', App\Livewire\Servidor\Avaliacoes\Index::class)->name('servidor.avaliacoes');
    Route::get('/avaliacoes/{avaliacao}/preencher', App\Livewire\Servidor\Avaliacoes\Form::class)->name('servidor.avaliacoes.form');
    Route::get('/avaliacoes/{avaliacao}/resultado', App\Livewire\Servidor\Avaliacoes\Resultado::class)->name('servidor.avaliacoes.resultado');
    Route::get('/historico', App\Livewire\Servidor\Historico\Index::class)->name('servidor.historico');
});
