<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::all();
        return response()->json($experiences);
    }

    public function show($id)
    {
        $experience = Experience::findOrFail($id);
        return response()->json($experience);
    }

    public function store(Request $request)
    {
        $experience = Experience::create($request->all());
        return response()->json($experience, 201);
    }

    public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);
        $experience->update($request->all());
        return response()->json($experience, 200);
    }

    public function destroy($id)
    {
        Experience::destroy($id);
        return response()->json(null, 204);
    }
}