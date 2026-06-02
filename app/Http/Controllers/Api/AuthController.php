<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    /**
     * Fitur Registrasi Akun Baru via API
     */
    #[OA\Post(
        path: "/api/v1/auth/register",
        summary: "Daftar Akun Baru",
        description: "Mendaftarkan user baru (contributor/upcycler).",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Budi"),
                    new OA\Property(property: "email", type: "string", example: "budi@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "password123"),
                    new OA\Property(property: "role", type: "string", example: "contributor"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Berhasil Daftar")
        ]
    )]
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
     * Mendukung dua cara autentikasi:
     * 1. JSON body { "email": ..., "password": ... }
     * 2. Basic Auth header: Authorization: Basic base64(email:password)
     */
    #[OA\Post(
        path: "/api/v1/auth/login",
        summary: "Login JWT / Basic Auth",
        description: "Mendapatkan JWT token dengan mengirimkan email dan password. Mendukung JSON body maupun Basic Auth.",
        tags: ["Auth"],
        security: [
            ["basicAuth" => []]
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "budi@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Berhasil Login (Dapat Token)")
        ]
    )]
    public function login(Request $request)
    {
        // Dukung Basic Auth: ambil kredensial dari Authorization header
        $authHeader = $request->header('Authorization', '');
        if (str_starts_with($authHeader, 'Basic ')) {
            $decoded = base64_decode(substr($authHeader, 6));
            [$basicEmail, $basicPass] = array_pad(explode(':', $decoded, 2), 2, '');
            if ($basicEmail && $basicPass) {
                $request->merge(['email' => $basicEmail, 'password' => $basicPass]);
            }
        }

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
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
    #[OA\Get(
        path: "/api/v1/auth/me",
        summary: "Lihat Profil Pengguna",
        description: "Mendapatkan data user yang sedang login berdasarkan JWT Token.",
        tags: ["Auth"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(response: 200, description: "Data user berhasil diambil")
        ]
    )]
    public function profile()
    {
        return response()->json(JWTAuth::user());
    }

    /**
     * Fitur Logout (Menghapus Validitas Token)
     */
    #[OA\Post(
        path: "/api/v1/auth/logout",
        summary: "Logout (Invalidate Token)",
        description: "Menghancurkan JWT Token yang sedang aktif.",
        tags: ["Auth"],
        security: [
            ["bearerAuth" => []]
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil Logout")
        ]
    )]
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