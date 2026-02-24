<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'

        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user, 201);
    }

    
    public function show($id)
    {
       
        $user = User::with('wallets')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $totalOverallBalance = $user->wallets->sum('balance');

        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'total_overall_balance' => $totalOverallBalance,
            'wallets' => $user->wallets
        ]);
    }
}