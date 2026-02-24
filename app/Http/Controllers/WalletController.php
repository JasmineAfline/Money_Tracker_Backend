<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'balance' => 'required|numeric'
        ]);

        $wallet = Wallet::create($validated);
        return response()->json($wallet, 201);
    }

    public function show($id)
    {
        return response()->json(Wallet::findOrFail($id));
    }
}