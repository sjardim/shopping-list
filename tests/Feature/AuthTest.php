<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('unauthenticated user is redirected to login from home', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});

test('unauthenticated user is redirected to login from add page', function () {
    $this->get(route('add'))->assertRedirect(route('login'));
});

test('unauthenticated user is redirected to login from history page', function () {
    $this->get(route('history'))->assertRedirect(route('login'));
});

test('login page loads successfully', function () {
    $this->get(route('login'))->assertOk();
});

test('user can log in with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('user cannot log in with invalid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('correct-password'),
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('authenticated user can access home page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk();
});
