<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Fitur Registrasi Akun Baru via API
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'in:contributor,upcycler', // Memastikan role valid
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'contributor', // Default menjadi contributor
            'city' => $request->city,
            'phone' => $request->phone,
        ]);

        // Otomatis generate token setelah berhasil daftar
        $token = JWTAuth::fromUser($user);

        // 🟢 FIXED: Status code di bawah diubah dari 21 menjadi 201 (Created) agar valid secara HTTP standar
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer'
        ], 201);
    }

    /**
     * Fitur Login untuk Mendapatkan Token JWT
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized, wrong email or password'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Fitur Ambil Data Profile User Berdasarkan Token JWT
     */
    public function profile()
    {
        return response()->json(JWTAuth::user());
    }

    /**
     * Fitur Logout (Menghapus Validitas Token)
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Format Response Token JWT
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60, 
            'user' => JWTAuth::user()
        ]);
    }
}