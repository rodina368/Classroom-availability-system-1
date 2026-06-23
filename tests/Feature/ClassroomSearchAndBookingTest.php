<?php

use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated user can access search page', function () {
    $response = $this->actingAs($this->user)->get(route('availability.search'));
    $response->assertStatus(200);
});

test('classroom availability search filters by capacity and location', function () {
    // Create classroom in North Building with capacity 30
    $room1 = Classroom::factory()->create([
        'name' => 'Room 101',
        'location' => 'North Building',
        'capacity' => 30,
        'is_active' => true,
    ]);

    // Create classroom in Science Block with capacity 10
    $room2 = Classroom::factory()->create([
        'name' => 'Room 201',
        'location' => 'Science Block',
        'capacity' => 10,
        'is_active' => true,
    ]);

    // Search for location 'North Building' and capacity 20
    $response = $this->actingAs($this->user)->get(route('availability.search', [
        'location' => 'North Building',
        'capacity' => 20,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Room 101');
    $response->assertDontSee('Room 201');
});

test('classroom shows correct free/occupied status in search results', function () {
    $classroom = Classroom::factory()->create([
        'name' => 'Room 101',
        'location' => 'Main Campus',
        'capacity' => 50,
        'is_active' => true,
    ]);

    // Create reservation for Room 101 from 10:00 to 12:00
    $date = '2026-06-15';
    $start = Carbon::parse("$date 10:00:00");
    $end = Carbon::parse("$date 12:00:00");

    Reservation::create([
        'classroom_id' => $classroom->id,
        'user_id' => $this->user->id,
        'start_time' => $start,
        'end_time' => $end,
        'purpose' => 'Test Booking',
        'status' => 'approved',
    ]);

    // Search for overlapping time 11:00 to 13:00 -> should show Occupied
    $response = $this->actingAs($this->user)->get(route('availability.search', [
        'date' => $date,
        'start_time' => '11:00',
        'end_time' => '13:00',
    ]));

    $response->assertStatus(200);
    $response->assertSee('Occupied');

    // Search for non-overlapping time 13:00 to 14:00 -> should show Free
    $response = $this->actingAs($this->user)->get(route('availability.search', [
        'date' => $date,
        'start_time' => '13:00',
        'end_time' => '14:00',
    ]));

    $response->assertStatus(200);
    $response->assertSee('Free');
});

test('booking a free classroom creates a reservation', function () {
    $classroom = Classroom::factory()->create([
        'name' => 'Room A',
        'location' => 'North Building',
        'capacity' => 20,
    ]);

    $response = $this->actingAs($this->user)->post(route('bookings.store'), [
        'classroom_id' => $classroom->id,
        'date' => '2026-06-10',
        'start_time' => '09:00',
        'end_time' => '10:00',
        'purpose' => 'Seminar',
    ]);

    $response->assertRedirect(route('classrooms.show', $classroom));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('reservations', [
        'classroom_id' => $classroom->id,
        'purpose' => 'Seminar',
    ]);
});

test('booking an occupied classroom triggers conflict detection and suggestions', function () {
    $classroom = Classroom::factory()->create([
        'name' => 'Conflict Room',
        'location' => 'Science Block',
        'capacity' => 25,
    ]);

    // Alternative room in the same location
    $altLocation = Classroom::factory()->create([
        'name' => 'Alt Same Location',
        'location' => 'Science Block',
        'capacity' => 20,
    ]);

    // Alternative room in different location with similar capacity
    $altCapacity = Classroom::factory()->create([
        'name' => 'Alt Similar Capacity',
        'location' => 'Arts Center',
        'capacity' => 26,
    ]);

    // Other occupied room (should not be suggested)
    $occupiedAlt = Classroom::factory()->create([
        'name' => 'Occupied Alt',
        'location' => 'Science Block',
        'capacity' => 30,
    ]);

    $date = '2026-06-20';
    $start = Carbon::parse("$date 14:00:00");
    $end = Carbon::parse("$date 15:00:00");

    // Book Conflict Room
    Reservation::create([
        'classroom_id' => $classroom->id,
        'user_id' => $this->user->id,
        'start_time' => $start,
        'end_time' => $end,
        'purpose' => 'Existing Reservation',
        'status' => 'approved',
    ]);

    // Book Occupied Alt as well
    Reservation::create([
        'classroom_id' => $occupiedAlt->id,
        'user_id' => $this->user->id,
        'start_time' => $start,
        'end_time' => $end,
        'purpose' => 'Other Reservation',
        'status' => 'approved',
    ]);

    // Now try to book Conflict Room for the same time
    $response = $this->actingAs($this->user)
        ->from(route('availability.search'))
        ->post(route('bookings.store'), [
            'classroom_id' => $classroom->id,
            'date' => $date,
            'start_time' => '14:00',
            'end_time' => '15:00',
            'purpose' => 'Second Reservation Attempt',
        ]);

    $response->assertRedirect(route('availability.search'));
    $response->assertSessionHasErrors(['classroom_id']);
    
    // Check suggestions in session
    $response->assertSessionHas('sameLocationAlternatives', function ($alts) use ($altLocation, $occupiedAlt) {
        $collection = collect($alts);
        return $collection->contains('id', $altLocation->id) && !$collection->contains('id', $occupiedAlt->id);
    });

    $response->assertSessionHas('similarCapacityAlternatives', function ($alts) use ($altCapacity, $occupiedAlt) {
        $collection = collect($alts);
        return $collection->contains('id', $altCapacity->id) && !$collection->contains('id', $occupiedAlt->id);
    });
});
