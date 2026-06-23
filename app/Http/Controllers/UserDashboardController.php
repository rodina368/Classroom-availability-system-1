<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Calculate upcoming reservations count
        $upcomingCount = $user->reservations()
            ->where('end_time', '>', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->count();

        // Calculate past reservations count
        $pastCount = $user->reservations()
            ->where('end_time', '<=', Carbon::now())
            ->count();

        // Calculate favourite classrooms count
        $favouritesCount = $user->favourites()->count();

        // Get the next 3 upcoming reservations
        $nextReservations = $user->reservations()
            ->with('classroom')
            ->where('end_time', '>', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        return view('dashboard.index', compact(
            'upcomingCount',
            'pastCount',
            'favouritesCount',
            'nextReservations'
        ));
    }
}
