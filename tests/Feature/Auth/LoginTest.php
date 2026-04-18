<?php

declare(strict_types=1);

use App\Models\Area;
use App\Models\Servidor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function criarServidor(string $perfil, ?string $email = null, bool $primeiroAcesso = false): array
{
    $area = $perfil !== 'admin' ? Area::factory()->create() : null;
    $email = $email ?? "{$perfil}@sgc.gov.br";

    $user = User::factory()->create([
        'email'    => $email,
        'password' => Hash::make('Senha@123'),
    ]);

    $servidor = Servidor::factory()->create([
        'user_id'         => $user->id,
        'perfil'          => $perfil,
        'status'          => 'ativo',
        'area_id'         => $area?->id,
        'primeiro_acesso' => $primeiroAcesso,
    ]);

    return compact('user', 'servidor');
}

it('exibe a tela de login para usuário não autenticado', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('Bem-vindo de volta');
});

it('redireciona admin para /admin/dashboard após login', function () {
    criarServidor('admin', 'admin@test.gov.br');

    $response = $this->post('/login', [
        'email'    => 'admin@test.gov.br',
        'password' => 'Senha@123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
});

it('redireciona gestor para /gestor/dashboard após login', function () {
    criarServidor('gestor', 'gestor@test.gov.br');

    $response = $this->post('/login', [
        'email'    => 'gestor@test.gov.br',
        'password' => 'Senha@123',
    ]);

    $response->assertRedirect(route('gestor.dashboard'));
});

it('redireciona servidor para /servidor/dashboard após login', function () {
    criarServidor('servidor', 'servidor@test.gov.br');

    $response = $this->post('/login', [
        'email'    => 'servidor@test.gov.br',
        'password' => 'Senha@123',
    ]);

    $response->assertRedirect(route('servidor.dashboard'));
});

it('bloqueia login com credenciais inválidas', function () {
    criarServidor('servidor', 'errado@test.gov.br');

    $response = $this->post('/login', [
        'email'    => 'errado@test.gov.br',
        'password' => 'senha-errada',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('redireciona para trocar-senha no primeiro acesso', function () {
    criarServidor('servidor', 'novo@test.gov.br', primeiroAcesso: true);

    $response = $this->post('/login', [
        'email'    => 'novo@test.gov.br',
        'password' => 'Senha@123',
    ]);

    $response->assertRedirect(route('trocar-senha'));
});

it('bloqueia acesso a /admin/* para gestor', function () {
    ['user' => $user] = criarServidor('gestor', 'ges@test.gov.br');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertRedirect(route('unauthorized'));
});

it('bloqueia acesso a /admin/* para servidor', function () {
    ['user' => $user] = criarServidor('servidor', 'srv@test.gov.br');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertRedirect(route('unauthorized'));
});

it('bloqueia acesso a /gestor/* para admin', function () {
    ['user' => $user] = criarServidor('admin', 'adm@test.gov.br');

    $this->actingAs($user)
        ->get('/gestor/dashboard')
        ->assertRedirect(route('unauthorized'));
});

it('bloqueia acesso a /servidor/* para admin', function () {
    ['user' => $user] = criarServidor('admin', 'adm2@test.gov.br');

    $this->actingAs($user)
        ->get('/servidor/dashboard')
        ->assertRedirect(route('unauthorized'));
});

it('bloqueia acesso a /servidor/* para gestor', function () {
    ['user' => $user] = criarServidor('gestor', 'ges2@test.gov.br');

    $this->actingAs($user)
        ->get('/servidor/dashboard')
        ->assertRedirect(route('unauthorized'));
});

it('bloqueia qualquer rota para servidor com primeiro_acesso=true', function () {
    ['user' => $user] = criarServidor('servidor', 'novo2@test.gov.br', primeiroAcesso: true);

    $this->actingAs($user)
        ->get('/servidor/dashboard')
        ->assertRedirect(route('trocar-senha'));
});

it('encerra sessão corretamente no logout', function () {
    ['user' => $user] = criarServidor('servidor', 'sair@test.gov.br');

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('redireciona usuário já logado de /login para seu dashboard', function () {
    ['user' => $user] = criarServidor('admin', 'ja_logado@test.gov.br');

    $this->actingAs($user)
        ->get('/login')
        ->assertRedirect(route('admin.dashboard'));
});

it('rota raiz redireciona para dashboard correto por perfil', function () {
    ['user' => $user] = criarServidor('servidor', 'raiz@test.gov.br');

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('servidor.dashboard'));
});
