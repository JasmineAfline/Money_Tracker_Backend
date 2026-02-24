<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    
    public function index($walletId)
    {
        $wallet = Wallet::find($walletId);

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $transactions = Transaction::where('wallet_id', $walletId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'wallet_name' => $wallet->name,
            'current_balance' => $wallet->balance,
            'transactions' => $transactions
        ]);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wallet_id'   => 'required|exists:wallets,id',
            'amount'      => 'required|numeric|min:0.01',
            'type'        => 'required|in:income,expense',
            'description' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($validated) {
            $wallet = Wallet::findOrFail($validated['wallet_id']);

            $transaction = Transaction::create($validated);

            if ($validated['type'] === 'income') {
                $wallet->balance += $validated['amount'];
            } else {
                $wallet->balance -= $validated['amount'];
            }

            $wallet->save();

            return response()->json([
                'message' => 'Transaction recorded successfully',
                'transaction' => $transaction,
                'new_balance' => $wallet->balance
            ], 201);
        });
    }

    
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $wallet = Wallet::findOrFail($transaction->wallet_id);

           
            if ($transaction->type === 'income') {
                $wallet->balance -= $transaction->amount;
            } else {
                $wallet->balance += $transaction->amount;
            }

            $wallet->save();
            $transaction->delete();

            return response()->json([
                'message' => 'Transaction deleted and balance updated',
                'new_balance' => $wallet->balance
            ]);
        });
    }
}