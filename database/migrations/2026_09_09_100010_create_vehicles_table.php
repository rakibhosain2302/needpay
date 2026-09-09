<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->restrictOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('color')->nullable();
            $table->string('registration_number')->unique();
            $table->date('registration_expiry_date')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended', 'inactive'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->index('driver_id');
            $table->index('vehicle_type_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
