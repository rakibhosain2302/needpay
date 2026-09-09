<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('license_number')->nullable()->unique();
            $table->date('license_expiry_date')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->boolean('is_online')->default(false);
            $table->enum('availability_status', ['offline', 'online', 'busy', 'on_trip'])->default('offline');
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->timestamp('last_location_at')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('total_trips')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('verification_status');
            $table->index('availability_status');
            $table->index('is_online');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
