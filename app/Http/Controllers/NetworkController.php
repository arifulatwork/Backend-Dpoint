<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    // 1. Get all users for networking (with optional filters)
    public function index(Request $request)
    {
        $authId = Auth::id();

        $query = User::query()->where('id', '!=', $authId);

        if ($request->has('location')) {
            $query->where('location', $request->location);
        }

        if ($request->has('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhereJsonContains('interests', $search);
            });
        }

        $users = $query->get();

        // Attach connection status
        $users = $users->map(function ($user) use ($authId) {
            $connection = Connection::where(function ($q) use ($authId, $user) {
                $q->where('requester_id', $authId)
                  ->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($authId, $user) {
                $q->where('requester_id', $user->id)
                  ->where('receiver_id', $authId);
            })->first();

            if (!$connection) {
                $status = 'none';
            } elseif ($connection->status === 'pending') {
                $status = 'pending';
            } elseif ($connection->status === 'accepted') {
                $status = 'connected';
            } else {
                $status = 'none'; // You can return 'rejected' or 'blocked' if needed
            }

            $user->connection_status = $status;
            return $user;
        });

        return response()->json($users);
    }

    // 2. Send a connection request
    public function sendConnectionRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $existing = Connection::where([
            'requester_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
        ])->first();

        if ($existing) {
            return response()->json(['message' => 'Connection request already sent'], 409);
        }

        $connection = Connection::create([
            'requester_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'status' => 'pending',
        ]);

        return response()->json($connection);
    }

    // 3. Accept or reject a request
    public function respondToRequest(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $connection = Connection::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->firstOrFail();

        $connection->status = $request->status;
        $connection->save();

        return response()->json($connection);
    }

    // 4. Get all your accepted connections
    public function myConnections()
    {
        $userId = Auth::id();

        $connections = Connection::where(function ($q) use ($userId) {
            $q->where('requester_id', $userId)
              ->orWhere('receiver_id', $userId);
        })
        ->where('status', 'accepted')
        ->with(['requester', 'receiver'])
        ->get();

        return response()->json($connections);
    }

    // 5. Get chat messages with a specific user
    public function getMessages($userId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)
              ->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', $authId);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    // 6. Send a new message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'read' => false,
        ]);

        return response()->json($message);
    }
}
