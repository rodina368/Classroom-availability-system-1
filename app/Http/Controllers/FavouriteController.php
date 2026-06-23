<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Auth::user()->favourites()->paginate(12);
        
        return view('favourites.index', compact('favourites'));
    }

    public function store(Classroom $classroom)
    {
        Auth::user()->favourites()->syncWithoutDetaching([$classroom->id]);
        
        return back()->with('success', __('Classroom added to favourites.'));
    }

    public function destroy(Classroom $classroom)
    {
        Auth::user()->favourites()->detach($classroom->id);
        
        return back()->with('success', __('Classroom removed from favourites.'));
    }
}
