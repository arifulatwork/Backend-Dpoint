<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();
        return response()->json($destinations);
    }

    public function show($id)
    {
        $destination = Destination::findOrFail($id);
        return response()->json($destination);
    }
}
