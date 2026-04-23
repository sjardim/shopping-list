<?php

test('unauthenticated user is redirected to login', function () {
    $this->get('/')->assertRedirect('/login');
});

test('login page loads successfully', function () {
    $this->get('/login')->assertOk();
});
