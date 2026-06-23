<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
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

        // Fetch distinct locations for the filter dropdown
        $locations = Classroom::where('is_active', true)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->pluck('location');

        $query = Classroom::where('is_active', true);

        if ($location) {
            $query->where('location', $location);
        }

        if ($capacity) {
            $query->where('capacity', '>=', $capacity);
        }

        // Paginate/get classrooms
        $classrooms = $query->get();

        $startDateTime = null;
        $endDateTime = null;
        $hasTimeFilter = $date && $startTime && $endTime;

        if ($hasTimeFilter) {
            $startDateTime = Carbon::parse($date . ' ' . $startTime);
            $endDateTime = Carbon::parse($date . ' ' . $endTime);
        }

        // Map status to each classroom
        $classrooms = $classrooms->map(function ($classroom) use ($startDateTime, $endDateTime, $hasTimeFilter) {
            if ($hasTimeFilter) {
                $isFree = $classroom->isAvailable($startDateTime, $endDateTime);
                $classroom->status = $isFree ? 'free' : 'occupied';
            } else {
                $classroom->status = 'free'; // default to free if no specific time range is queried
            }
            return $classroom;
        });

        // Let's paginate manually or just pass the collection for simple views
        return view('availability.index', compact(
            'classrooms',
            'locations',
            'date',
            'startTime',
            'endTime',
            'location',
            'capacity'
        ));
    }
}
