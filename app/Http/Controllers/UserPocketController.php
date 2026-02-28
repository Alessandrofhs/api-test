<?php

namespace App\Http\Controllers;

use App\Models\UserPocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPocketController extends Controller
{
    public function index()
    {
        $pockets = UserPocket::where('user_id', Auth::id())
            ->select('id', 'name', 'balance as current_balance')
            ->get();

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Berhasil.',
            'data' => $pockets
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'initial_balance' => 'required|numeric|min:0',
        ]);

        $pocket = UserPocket::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'balance' => $request->initial_balance,
        ]);

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Berhasil membuat pocket baru.',
            'data' => [
                'id' => $pocket->id
            ]
        ]);
    }
     public function totalBalance()
    {
        $pockets = UserPocket::where('user_id', Auth::id())
            ->select('balance as total')
            ->get();

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Berhasil.',
            'data' => $pockets
        ]);
    }
}
