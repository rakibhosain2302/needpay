<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('ride_id')->nullable()->constrained('rides')->restrictOnDelete();
            $table->decimal('discount_amount', 12, 2);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index('promo_code_id');
            $table->index('user_id');
            $table->index('ride_id');
            $table->unique(['promo_code_id', 'ride_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_usages');
    }
};
