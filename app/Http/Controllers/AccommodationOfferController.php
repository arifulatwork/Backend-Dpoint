<?php

namespace App\Http\Controllers;

use App\Models\AccommodationOffer;
use Illuminate\Http\Request;

class AccommodationOfferController extends Controller
{
    public function index()
    {
        $offers = AccommodationOffer::all();
        return response()->json($offers);
    }
}