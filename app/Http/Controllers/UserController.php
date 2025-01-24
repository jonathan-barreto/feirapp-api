<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $firstErrorMessage = collect($errors->messages())->flatten()->first();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => "Erro de validação: $firstErrorMessage",
            ]);
        }

        try {
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Usuário cadastrado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Ocorreu um erro ao cadastrar o usuário.',
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = $request->user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                    ],
                    'message' => 'Login realizado com sucesso!',
                ]);
            }

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Ocorreu um erro ao realizar o login.',
            ]);
        }
    }

    public function show(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $request->user(),
                'message' => 'Dados do usuário recuperados com sucesso.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Ocorreu um erro ao recuperar os dados do usuário.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Logout realizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Ocorreu um erro ao realizar o logout.',
            ]);
        }
    }
}
