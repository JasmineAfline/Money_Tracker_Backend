<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

// User Routes
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);

// Wallet Routes
Route::post('/wallets', [WalletController::class, 'store']);
Route::get('/wallets/{id}', [WalletController::class, 'show']);

// Transaction Routes
Route::post('/transactions', [TransactionController::class, 'store']);
// NEW: Get all transactions for a specific wallet
Route::get('/wallets/{id}/transactions', [TransactionController::class, 'index']);

// Delete a transaction
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);