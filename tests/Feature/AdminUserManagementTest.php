<?php

use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;
use Carbon\Carbon;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'administrator']);
    $this->student = User::factory()->create(['role' => 'student']);
});

test('non-admins cannot access user management list or update roles', function () {
    $this->actingAs($this->student)->get(route('admin.users.index'))->assertStatus(403);
    
    $this->actingAs($this->student)->patch(route('admin.users.updateRole', $this->admin), [
        'role' => 'student'
    ])->assertStatus(403);
});

test('admins can view users list', function () {
    $this->actingAs($this->admin)->get(route('admin.users.index'))
        ->assertStatus(200)
        ->assertSee($this->student->name)
        ->assertSee($this->admin->name);
});

test('admins can change user roles but not their own role', function () {
    // Promote student to administrator
    $response = $this->actingAs($this->admin)->patch(route('admin.users.updateRole', $this->student), [
        'role' => 'administrator'
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success');
    expect($this->student->refresh()->role)->toBe('administrator');

    // Demote user to lecturer
    $response = $this->actingAs($this->admin)->patch(route('admin.users.updateRole', $this->student), [
        'role' => 'lecturer'
    ]);
    expect($this->student->refresh()->role)->toBe('lecturer');

    // Try to demote self (should fail)
    $response = $this->actingAs($this->admin)->patch(route('admin.users.updateRole', $this->admin), [
        'role' => 'student'
    ]);
    $response->assertSessionHasErrors(['role']);
    expect($this->admin->refresh()->role)->toBe('administrator');
});

test('booking conflict suggestions respect search capacity filters', function () {
    $classroom = Classroom::factory()->create([
        'name' => 'Conflict Room',
        'location' => 'Block X',
        'capacity' => 20,
    ]);

    // Same location alternatives:
    // One meets search capacity of 30, one does not.
    $altSameLocationPass = Classroom::factory()->create([
        'name' => 'Alt Same Location Pass',
        'location' => 'Block X',
        'capacity' => 35,
    ]);
    $altSameLocationFail = Classroom::factory()->create([
        'name' => 'Alt Same Location Fail',
        'location' => 'Block X',
        'capacity' => 25,
    ]);

    // Similar capacity alternatives (in other locations):
    // One meets search capacity of 30, one does not.
    $altSimilarPass = Classroom::factory()->create([
        'name' => 'Alt Similar Pass',
        'location' => 'Block Y',
        'capacity' => 32,
    ]);
    $altSimilarFail = Classroom::factory()->create([
        'name' => 'Alt Similar Fail',
        'location' => 'Block Y',
        'capacity' => 28,
    ]);

    $date = '2026-06-25';
    $start = Carbon::parse("$date 11:00:00");
    $end = Carbon::parse("$date 12:00:00");

    // Book the room to create conflict
    Reservation::create([
        'classroom_id' => $classroom->id,
        'user_id' => $this->admin->id,
        'start_time' => $start,
        'end_time' => $end,
        'purpose' => 'Existing Meeting',
        'status' => 'approved',
    ]);

    // Post booking request for conflicted room with search_capacity = 30
    $response = $this->actingAs($this->admin)
        ->from(route('availability.search'))
        ->post(route('bookings.store'), [
            'classroom_id' => $classroom->id,
            'date' => $date,
            'start_time' => '11:00',
            'end_time' => '12:00',
            'purpose' => 'Conflicted Attempt',
            'search_capacity' => '30',
        ]);

    $response->assertRedirect(route('availability.search'));
    $response->assertSessionHasErrors(['classroom_id']);

    // Check same location suggestions only include rooms with capacity >= 30
    $response->assertSessionHas('sameLocationAlternatives', function ($alts) use ($altSameLocationPass, $altSameLocationFail) {
        $collection = collect($alts);
        return $collection->contains('id', $altSameLocationPass->id) && !$collection->contains('id', $altSameLocationFail->id);
    });

    // Check similar capacity suggestions only include rooms with capacity >= 30
    $response->assertSessionHas('similarCapacityAlternatives', function ($alts) use ($altSimilarPass, $altSimilarFail) {
        $collection = collect($alts);
        return $collection->contains('id', $altSimilarPass->id) && !$collection->contains('id', $altSimilarFail->id);
    });
});
