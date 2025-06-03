<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCardController extends Controller
{
    // List all credit cards for the authenticated user
    public function index()
    {
        return response()->json(Auth::user()->creditCards);
    }

    // Store a new credit card
    public function store(Request $request)
    {
        $request->validate([
            'last4' => 'required|string|size:4',
            'expiry' => 'required|string',
            'type' => 'required|in:visa,mastercard',
        ]);

        $card = new CreditCard([
            'last4' => $request->last4,
            'expiry' => $request->expiry,
            'type' => $request->type,
            'is_default' => Auth::user()->creditCards()->count() === 0,
        ]);

        Auth::user()->creditCards()->save($card);

        return response()->json(['message' => 'Card added successfully', 'card' => $card]);
    }

    // Set a card as the default
    public function setDefault($id)
    {
        $user = Auth::user();

        $card = $user->creditCards()->where('id', $id)->firstOrFail();

        // Unset all other cards
        $user->creditCards()->update(['is_default' => false]);

        // Set selected card as default
        $card->is_default = true;
        $card->save();

        return response()->json(['message' => 'Card set as default']);
    }

    // Delete a card
    public function destroy($id)
    {
        $user = Auth::user();
        $card = $user->creditCards()->where('id', $id)->firstOrFail();

        $card->delete();

        return response()->json(['message' => 'Card removed successfully']);
    }
}

