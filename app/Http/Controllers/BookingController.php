<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:255',
        ]);

        $classroomId = $request->input('classroom_id');
        $date = $request->input('date');
        $startTimeStr = $request->input('start_time');
        $endTimeStr = $request->input('end_time');
        $purpose = $request->input('purpose');

        $startDateTime = Carbon::parse($date . ' ' . $startTimeStr);
        $endDateTime = Carbon::parse($date . ' ' . $endTimeStr);

        $classroom = Classroom::findOrFail($classroomId);

        // Check for conflicts
        $isAvailable = $classroom->isAvailable($startDateTime, $endDateTime);

        if (!$isAvailable) {
            // Conflict detected!
            $searchCapacity = $request->input('search_capacity');

            // 1. Suggest other available classrooms in the SAME location
            $sameLocationQuery = Classroom::where('id', '!=', $classroom->id)
                ->where('is_active', true)
                ->where('location', $classroom->location)
                ->availableBetween($startDateTime, $endDateTime);

            if ($searchCapacity) {
                $sameLocationQuery->where('capacity', '>=', (int)$searchCapacity);
            }

            $sameLocationAlternatives = $sameLocationQuery->get();

            // 2. Suggest classrooms with similar capacity
            $similarCapacityQuery = Classroom::where('id', '!=', $classroom->id)
                ->where('is_active', true)
                ->availableBetween($startDateTime, $endDateTime);

            if ($searchCapacity) {
                // If a capacity criteria was set, find rooms meeting that constraint
                $similarCapacityQuery->where('capacity', '>=', (int)$searchCapacity)
                    ->orderBy('capacity', 'asc');
            } else {
                // Otherwise, suggest classrooms within similar capacity (+/- 15 seats) of conflicted room
                $capacityRangeMin = max(1, $classroom->capacity - 15);
                $capacityRangeMax = $classroom->capacity + 15;
                $similarCapacityQuery->whereBetween('capacity', [$capacityRangeMin, $capacityRangeMax]);
            }

            $similarCapacityAlternatives = $similarCapacityQuery->take(5)->get();

            // Fallback: If similarity filter is empty, grab any available classrooms and sort by capacity difference
            if ($similarCapacityAlternatives->isEmpty()) {
                $allAvailable = Classroom::where('id', '!=', $classroom->id)
                    ->where('is_active', true)
                    ->availableBetween($startDateTime, $endDateTime)
                    ->get();
                
                $similarCapacityAlternatives = $allAvailable->sortBy(function ($c) use ($classroom) {
                    return abs($c->capacity - $classroom->capacity);
                })->take(5)->values();
            }

            // Find who booked the conflicting reservation
            $conflictingReservation = $classroom->reservations()
                ->where('start_time', '<', $endDateTime)
                ->where('end_time', '>', $startDateTime)
                ->where('status', '!=', 'cancelled')
                ->with('user')
                ->first();

            $bookedByName = $conflictingReservation?->user?->name ?? 'Another user';

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'classroom_id' => "Conflict detected: {$bookedByName} has already booked this classroom."
                ])
                ->with('sameLocationAlternatives', $sameLocationAlternatives->toArray())
                ->with('similarCapacityAlternatives', $similarCapacityAlternatives->toArray())
                ->with('conflictClassroomName', $classroom->name)
                ->with('conflictClassroomLocation', $classroom->location)
                ->with('conflictClassroomCapacity', $classroom->capacity)
                ->with('conflictStart', $startDateTime->format('Y-m-d H:i'))
                ->with('conflictEnd', $endDateTime->format('Y-m-d H:i'));
        }

        // Create booking/reservation
        Reservation::create([
            'classroom_id' => $classroom->id,
            'user_id' => Auth::id() ?? 1, // Fallback to user with ID 1 (admin) if user session is absent/testing
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'purpose' => $purpose,
            'status' => 'approved',
        ]);

        return redirect()->route('classrooms.show', $classroom)
            ->with('success', __('Classroom successfully booked!'));
    }
}
