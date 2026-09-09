<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('minimum_fare', 12, 2);
            $table->decimal('base_fare', 12, 2);
            $table->decimal('per_km_rate', 12, 2);
            $table->decimal('per_minute_rate', 12, 2);
            $table->decimal('waiting_charge_per_minute', 12, 2)->default(0);
            $table->unsignedTinyInteger('max_passengers')->default(4);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
