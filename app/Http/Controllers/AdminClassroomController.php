<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class AdminClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::orderBy('name')->paginate(10);
        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('admin.classrooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'equipment' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $equipmentArray = [];
        if (!empty($validated['equipment'])) {
            $equipmentArray = array_values(array_filter(array_map('trim', explode(',', $validated['equipment']))));
        }

        Classroom::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'],
            'equipment' => $equipmentArray,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', __('Classroom successfully created!'));
    }

    public function edit(Classroom $classroom)
    {
        $equipmentString = $classroom->equipment ? implode(', ', $classroom->equipment) : '';
        return view('admin.classrooms.edit', compact('classroom', 'equipmentString'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'equipment' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $equipmentArray = [];
        if (!empty($validated['equipment'])) {
            $equipmentArray = array_values(array_filter(array_map('trim', explode(',', $validated['equipment']))));
        }

        $classroom->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity'],
            'equipment' => $equipmentArray,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', __('Classroom successfully updated!'));
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')
            ->with('success', __('Classroom successfully deleted!'));
    }
}
