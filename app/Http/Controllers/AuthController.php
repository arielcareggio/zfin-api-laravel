<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($request->input('origen'));

            $token->accessToken->expires_at = Carbon::now()->addHours(48);
            $token->accessToken->save();
            $userId = auth()->user()->id;
            $name = auth()->user()->name;
            $lastName = auth()->user()->last_name;
            $email = auth()->user()->email;
            $id_countrie = auth()->user()->id_countrie;
            $phone = auth()->user()->phone;

            return response()->json([
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $token->accessToken->expires_at,
                'data' => [
                    'user_id' => $userId,
                    'name' => $name,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone
                ]
            ]);
        } else {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Cierre de sesión exitoso']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'last_name' => 'nullable|string', //opcional
            'phone' => 'nullable|string', //opcional
            'id_countrie' => 'nullable|integer', //opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'id_countrie' => $request->input('id_countrie'),
        ]);

        return response()->json(['message' => 'Usuario registrado correctamente'], 201);
    }
}
