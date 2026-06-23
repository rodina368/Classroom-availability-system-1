<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'university_id' => 'TEST-0001',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'student',
        'department' => 'Computer Science',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users cannot register as administrator', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Fake Admin',
        'university_id' => 'ADMIN-FAKE',
        'email' => 'fakeadmin@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'administrator',
        'department' => 'Administration',
    ]);

    $response->assertSessionHasErrors(['role']);
    $this->assertGuest();
});

