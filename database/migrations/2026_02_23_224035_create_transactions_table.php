<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Link to the wallet
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            
            // Enum restricts the 'type' to only these two options
            $table->enum('type', ['income', 'expense']);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};