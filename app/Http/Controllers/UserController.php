<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

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
                'number_contact' => 'required|string|max:15',
            ]);
    
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), 
                'number_contact' => $validatedData['number_contact'],
            ]);
            
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
