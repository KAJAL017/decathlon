<?php

test('the admin login page renders successfully', function () {
    if (config('database.default') === 'sqlite') {
        $this->markTestSkipped('SQLite does not support fulltext migrations in this setup.');
    }

    $response = $this->get('/admin/login');

    $response->assertStatus(200);
    $response->assertSee('Admin Login');
    $response->assertSee('email');
    $response->assertSee('password');
});

test('admin login with invalid credentials returns 401 unauthorized', function () {
    if (config('database.default') === 'sqlite') {
        $this->markTestSkipped('SQLite does not support fulltext migrations in this setup.');
    }

    $response = $this->postJson('/admin/login', [
        'email' => 'wrong@gmail.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(401);
    $response->assertJson([
        'success' => false,
        'message' => 'Invalid email or password. Please try again.'
    ]);
});

test('admin login with empty values returns 422 validation error', function () {
    if (config('database.default') === 'sqlite') {
        $this->markTestSkipped('SQLite does not support fulltext migrations in this setup.');
    }

    $response = $this->postJson('/admin/login', [
        'email' => '',
        'password' => ''
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

test('forgot password page renders successfully', function () {
    if (config('database.default') === 'sqlite') {
        $this->markTestSkipped('SQLite does not support fulltext migrations in this setup.');
    }

    $response = $this->get('/admin/forgot-password');
    $response->assertStatus(200);
    $response->assertSee('Forgot Password');
});

test('forgot password with non-existent email returns error', function () {
    if (config('database.default') === 'sqlite') {
        $this->markTestSkipped('SQLite does not support fulltext migrations in this setup.');
    }

    $response = $this->postJson('/admin/forgot-password', [
        'email' => 'fakeadmin@decathlon.com'
    ]);
    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'This email address is not registered in our system.'
    ]);
});
