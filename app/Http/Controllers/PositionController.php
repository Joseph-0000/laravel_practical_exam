<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $query = Position::query();

        if ($request->has('search')) {
            $query->where('position', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('position', $request->sort);
        }

        return response()->json($query->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'position' => ['required', 'unique:positions,position'],
            'reports_to' => ['nullable', 'exists:positions,id'],
        ]);

        if ($request->reports_to === null && Position::whereNull('reports_to')->exists()) {
            return response()->json(['error' => 'Only one position can have a null reports_to']);
        }

        $position = Position::create($validated);
        return response()->json($position);
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
         return response()->json($position);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
         $validated = $request->validate([
            'position' => ['required', Rule::unique('positions', 'position')->ignore($position->id)],
            'reports_to' => ['nullable', 'exists:positions,id'],
        ]);

        // Ensure only one position can have null "reports_to"
        if ($request->reports_to === null && Position::whereNull('reports_to')->where('id', '!=', $position->id)->exists()) {
            return response()->json(['error' => 'Only one position can have a null reports_to'], 422);
        }

        $position->update($validated);
        return response()->json($position);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
           $position->delete();
        return response()->json(['message' => 'Position deleted successfully']);
    }
}
