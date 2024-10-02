<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'username' => 'required|string|min:4|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('customer');

        // Generate a token for the user
        $token = $user->createToken('user_token')->plainTextToken;


        // Return a success response with the token
        return response()->json([
            'message' => 'User successfully registered',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        // return 'is login';
        $user = User::whereUsername($request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Credential are incorect.'],
            ], 422);
        }

        $token = $user->createToken('user_token')->plainTextToken;
        $role = $user->getRoleNames()->first();


        // Return a success response with the token
        return response()->json([
            'message' => 'User successfully login',
            // 'user' => $user,
            'user' => [
                'name' => $user->name,
                'role' => $role, // Role dikirimkan ke Flutter
                'token' => $token,
            ]
            // 'access_token' => $token,
        ], 201);

        // // Ambil role pertama user
        // $role = $user->getRoleNames()->first();

        // // Kembalikan respons JSON dengan informasi user, role, dan token
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Login berhasil',
        //     'user' => [
        //         'name' => $user->name,
        //         'role' => $role, // Role dikirimkan ke Flutter
        //         'token' => $token,
        //     ]
        // ]);
    }
}
