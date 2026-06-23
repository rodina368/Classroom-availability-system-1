<?php

use App\Models\User;

test('api user can register successfully with valid data', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'university_id' => 'UNI-12345',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone_number' => '+123456789',
        'role' => 'student',
        'department' => 'Computer Science',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'User registered successfully',
            'data' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'student',
            ]
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'university_id' => 'UNI-12345',
        'role' => 'student',
        'department' => 'Computer Science',
    ]);
});

test('api user registration fails with invalid data', function () {
    $response = $this->postJson('/api/register', [
        'name' => '',
        'university_id' => '',
        'email' => 'not-an-email',
        'password' => 'pass',
        'password_confirmation' => 'diff-pass',
        'role' => 'invalid_role',
        'department' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'university_id',
                'email',
                'password',
                'role',
                'department',
            ]
        ]);

    $response->assertJsonFragment([
        'message' => 'Validation failed'
    ]);
});

test('api user registration fails on non-unique fields', function () {
    // Create an existing user
    User::factory()->create([
        'email' => 'duplicate@example.com',
        'university_id' => 'DUP-1111',
    ]);

    $response = $this->postJson('/api/register', [
        'name' => 'Jane Doe',
        'university_id' => 'DUP-1111',
        'email' => 'duplicate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'lecturer',
        'department' => 'Mathematics',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'university_id',
            ]
        ]);
});

test('api user cannot register as administrator', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Fake Admin API',
        'university_id' => 'ADMIN-API-FAKE',
        'email' => 'fakeadminapi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'administrator',
        'department' => 'Administration',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'role',
            ]
        ]);
    
    $this->assertDatabaseMissing('users', ['email' => 'fakeadminapi@example.com']);
});

