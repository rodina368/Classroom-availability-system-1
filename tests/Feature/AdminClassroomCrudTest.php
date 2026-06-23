<?php

use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'administrator']);
    $this->student = User::factory()->create(['role' => 'student']);
});

test('non-admin user is forbidden from accessing admin classroom routes', function () {
    // List
    $this->actingAs($this->student)->get(route('admin.classrooms.index'))->assertStatus(403);
    
    // Create view
    $this->actingAs($this->student)->get(route('admin.classrooms.create'))->assertStatus(403);
    
    // Store
    $this->actingAs($this->student)->post(route('admin.classrooms.store'), [
        'name' => 'New Room',
        'location' => 'Building A',
        'capacity' => 10,
    ])->assertStatus(403);

    // Edit view
    $classroom = Classroom::factory()->create();
    $this->actingAs($this->student)->get(route('admin.classrooms.edit', $classroom))->assertStatus(403);

    // Update
    $this->actingAs($this->student)->put(route('admin.classrooms.update', $classroom), [
        'name' => 'Updated Name',
        'location' => 'Building B',
        'capacity' => 20,
    ])->assertStatus(403);

    // Destroy
    $this->actingAs($this->student)->delete(route('admin.classrooms.destroy', $classroom))->assertStatus(403);
});

test('admin user can access index, create, and edit pages', function () {
    $classroom = Classroom::factory()->create();

    $this->actingAs($this->admin)->get(route('admin.classrooms.index'))->assertStatus(200);
    $this->actingAs($this->admin)->get(route('admin.classrooms.create'))->assertStatus(200);
    $this->actingAs($this->admin)->get(route('admin.classrooms.edit', $classroom))->assertStatus(200);
});

test('admin can create a new classroom with equipment parsed', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.classrooms.store'), [
        'name' => 'Admin Lab 101',
        'location' => 'Engineering Block',
        'capacity' => 45,
        'equipment' => 'projector, whiteboard, 3D printer',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.classrooms.index'));
    $response->assertSessionHas('success');

    $classroom = Classroom::where('name', 'Admin Lab 101')->first();
    expect($classroom)->not->toBeNull();
    expect($classroom->location)->toBe('Engineering Block');
    expect($classroom->capacity)->toBe(45);
    expect($classroom->is_active)->toBeTrue();
    expect($classroom->equipment)->toBe(['projector', 'whiteboard', '3D printer']);
});

test('admin can update a classroom', function () {
    $classroom = Classroom::factory()->create([
        'name' => 'Original Room',
        'location' => 'Old Block',
        'capacity' => 15,
        'equipment' => ['projector'],
        'is_active' => false,
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.classrooms.update', $classroom), [
        'name' => 'Modified Room Name',
        'location' => 'New Block',
        'capacity' => 25,
        'equipment' => 'projector, sound system',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.classrooms.index'));
    $response->assertSessionHas('success');

    $classroom->refresh();
    expect($classroom->name)->toBe('Modified Room Name');
    expect($classroom->location)->toBe('New Block');
    expect($classroom->capacity)->toBe(25);
    expect($classroom->is_active)->toBeTrue();
    expect($classroom->equipment)->toBe(['projector', 'sound system']);
});

test('admin can delete a classroom and cascading removes reservations', function () {
    $classroom = Classroom::factory()->create();

    // Create a reservation for this classroom
    Reservation::create([
        'classroom_id' => $classroom->id,
        'user_id' => $this->admin->id,
        'start_time' => now()->addHour(),
        'end_time' => now()->addHours(2),
        'purpose' => 'Meeting',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.classrooms.destroy', $classroom));

    $response->assertRedirect(route('admin.classrooms.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    $this->assertDatabaseMissing('reservations', ['classroom_id' => $classroom->id]);
});
