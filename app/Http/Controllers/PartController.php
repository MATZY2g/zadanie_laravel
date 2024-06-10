<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Car;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parts = Part::all();
        return response()->json($parts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'serialnumber' => 'required|unique:parts,serialnumber',
            'car_id' => 'required|exists:cars,id'
        ]);

        $part = Part::create($request->all());
        return response()->json($part, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json(['message' => 'Part not found'], 404);
        }

        return response()->json($part);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json(['message' => 'Part not found'], 404);
        }

        $request->validate([
            'name' => 'required',
            'serialnumber' => 'required|unique:parts,serialnumber,' . $id,
            'car_id' => 'required|exists:cars,id'
        ]);

        $part->update($request->all());
        return response()->json($part);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $part = Part::find($id);

        if (!$part) {
            return response()->json(['message' => 'Part not found'], 404);
        }

        $part->delete();
        return response()->json(['message' => 'Part deleted successfully']);
    }
}
