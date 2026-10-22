<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitantes conseguem acessar a tela de login', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertViewIs('auth.login');
});

test('visitantes não conseguem acessar o dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('usuários conseguem fazer login com credenciais válidas', function () {
    $user = User::factory()->create([
        'password' => 'SenhaSegura123!',
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'SenhaSegura123!',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('o sistema rejeita senhas incorretas', function () {
    $user = User::factory()->create([
        'password' => 'SenhaSegura123!',
    ]);

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'SenhaIncorreta123!',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('o sistema valida os campos obrigatórios no login', function () {
    $response = $this->post(route('login'), [
        'email' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors([
        'email',
        'password',
    ]);

    $this->assertGuest();
});

test('usuários autenticados conseguem fazer logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('logout'));

    $response->assertRedirect(route('login'));

    $this->assertGuest();
});

test('usuários autenticados são redirecionados ao acessar o login', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('login'));

    $response->assertRedirect(route('dashboard'));
});

test('usuários excluídos logicamente não conseguem fazer login', function () {
    $user = User::factory()->create([
        'password' => 'SenhaSegura123!',
    ]);

    $user->delete();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'SenhaSegura123!',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});
