<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use Carbon\Carbon;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i|required_with:end_time',
            'end_time' => 'nullable|date_format:H:i|after:start_time|required_with:start_time',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $date = $request->input('date', now()->format('Y-m-d'));
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $location = $request->input('location');
        $capacity = $request->input('capacity');
        $search = $request->input('q');

        // Fetch distinct locations for filter dropdown
        $locations = Classroom::where('is_active', true)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->pluck('location');

        $query = Classroom::where('is_active', true);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($location) {
            $query->where('location', $location);
        }

        if ($capacity) {
            $query->where('capacity', '>=', $capacity);
        }

        $classrooms = $query->orderBy('name')->get();

        // Compute free/occupied status
        $hasTimeFilter = $startTime && $endTime;
        $startDateTime = null;
        $endDateTime = null;

        if ($hasTimeFilter) {
            $startDateTime = Carbon::parse($date . ' ' . $startTime);
            $endDateTime = Carbon::parse($date . ' ' . $endTime);
        }

        $classrooms = $classrooms->map(function ($classroom) use ($startDateTime, $endDateTime, $hasTimeFilter) {
            if ($hasTimeFilter) {
                $classroom->status = $classroom->isAvailable($startDateTime, $endDateTime) ? 'free' : 'occupied';
            } else {
                // Check if occupied right now (current hour slot)
                $now = Carbon::now();
                $oneHourLater = $now->copy()->addHour();
                $classroom->status = $classroom->isAvailable($now, $oneHourLater) ? 'free' : 'occupied';
            }
            return $classroom;
        });

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($classrooms);
        }

        return view('classrooms.index', compact(
            'classrooms',
            'locations',
            'date',
            'startTime',
            'endTime',
            'location',
            'capacity',
            'search'
        ));
    }

    public function show(Classroom $classroom)
    {
        $reservations = $classroom->reservations()
            ->where('end_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get();
            
        return view('classrooms.show', compact('classroom', 'reservations'));
    }
}
