<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors([
                'role' => 'You cannot modify your own role.'
            ]);
        }

        $validated = $request->validate([
            'role' => 'required|string|in:student,lecturer,administrator',
        ]);

        $user->update([
            'role' => $validated['role']
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('Role for :name successfully updated to :role!', ['name' => $user->name, 'role' => __(ucfirst($validated['role']))]));
    }
}
