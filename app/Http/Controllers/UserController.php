<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        //
    }

    public function store(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'whatsapp' => 'required|string|max:15',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            $firstErrorMessage = collect($errors->messages())->flatten()->first();

            $userResource = new UserResource(null, $firstErrorMessage);

            return response()->json($userResource->toArray($request), 422);
        }

        try {
            //code...
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'whatsapp' => $validatedData['whatsapp'],
            ]);

            return new UserResource(null, 'Registro realizado com sucesso!');
        } catch (\Exception $e) {
            return new UserResource(null, $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $cretendials = $request->only('email', 'password');

        $tokenData = [];

        if (Auth::attempt($cretendials)) {
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $tokenData = [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];

            return response()->json([
                'data' => $tokenData,
                'message' => 'Login realizado com sucesso!',
            ]);
        }

        return response()->json([
            'data' => $tokenData,
            'message' => 'Usúario inválido',
        ]);
    }

    public function show(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
            'message' => '',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'data' => [],
            'message' => 'Logout realizado com sucesso!',
        ]);
    }
}
