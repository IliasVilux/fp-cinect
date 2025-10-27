<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseHas;

// REGISTER

test('register screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});


test('users can register with valid data', function () {
    $response = $this->followingRedirects()->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertInertia(fn (Assert $page) =>
        $page->component('dashboard/Dashboard')
            ->has('auth.user', fn ($user) =>
                $user->where('name', 'John Doe')
                     ->where('email', 'john@example.com')
                     ->etc()
            )
    );
    assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('registration fails with invalid data', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'different',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertGuest();
});

test('registration fails with duplicate email', function () {
    $user = User::factory()->create();

    $response = $this->post('/register', [
        'name' => 'Jane',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

// LOGIN

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard.index', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('authenticated user is redirected away from login and register pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get('/login')->assertRedirect(route('home', absolute: false));
    $this->get('/register')->assertRedirect(route('home', absolute: false));
});
