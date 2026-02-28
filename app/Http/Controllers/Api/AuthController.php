<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $token = Auth::guard('api')->attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 401,
                'error' => true,
                'message' => 'Email atau password salah.',
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Berhasil login.',
            'data' => [
                'token' => $token
            ]
        ]);
    }
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Berhasil ambil profile.',
            'data' => [
                'full_name' => $user->full_name,
                'email' => $user->email
            ]
        ]);
    }
}
