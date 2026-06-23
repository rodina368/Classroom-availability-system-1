<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $today = today();

        // Classroom Stats
        $totalClassrooms = Classroom::count();
        $activeClassrooms = Classroom::where('is_active', true)->count();

        // Occupied classrooms right now (must be approved, within time, and active rooms only theoretically)
        $occupiedClassrooms = Reservation::where('status', 'approved')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->distinct('classroom_id')
            ->count('classroom_id');

        // Available Classrooms
        $availableClassrooms = max(0, $activeClassrooms - $occupiedClassrooms);

        // Upcoming bookings today (starting in the future, but today)
        $upcomingBookingsToday = Reservation::where('status', 'approved')
            ->whereDate('start_time', $today)
            ->where('start_time', '>', $now)
            ->count();

        // User Stats
        $studentsCount = User::where('role', 'student')->count();
        $instructorsCount = User::where('role', 'lecturer')->count();

        // Utilization Rate (Current occupied vs total active)
        $utilizationRate = $activeClassrooms > 0 
            ? round(($occupiedClassrooms / $activeClassrooms) * 100) 
            : 0;

        // Recent Bookings
        $recentBookings = Reservation::with(['user', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalClassrooms',
            'availableClassrooms',
            'occupiedClassrooms',
            'upcomingBookingsToday',
            'studentsCount',
            'instructorsCount',
            'utilizationRate',
            'recentBookings'
        ));
    }
}
