<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        try {
            //code...
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'number_contact' => 'required|string|max:15',
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
                'number_contact' => $validatedData['number_contact'],
            ]);

            return new UserResource(null, 'Registro realizado com sucesso!');
        } catch (\Exception $e) {
            return new UserResource(null, $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        //
    }
}
