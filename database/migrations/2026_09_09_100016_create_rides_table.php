<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_request_id')->unique()->constrained('ride_requests')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('accepted_bid_id')->nullable()->unique()->constrained('ride_bids')->restrictOnDelete();

            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 7);
            $table->decimal('pickup_longitude', 10, 7);
            $table->string('destination_address');
            $table->decimal('destination_latitude', 10, 7);
            $table->decimal('destination_longitude', 10, 7);

            $table->timestamp('driver_arrived_at')->nullable();
            $table->timestamp('trip_started_at')->nullable();
            $table->timestamp('trip_completed_at')->nullable();

            $table->decimal('actual_distance_km', 8, 2)->nullable();
            $table->unsignedInteger('actual_duration_minutes')->nullable();
            $table->decimal('final_fare', 12, 2)->nullable();
            $table->decimal('waiting_charge', 12, 2)->default(0);
            $table->decimal('cancellation_fee', 12, 2)->default(0);

            $table->enum('status', [
                'assigned', 'driver_arriving', 'driver_arrived',
                'in_progress', 'completed', 'cancelled',
            ])->default('assigned');

            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('driver_id');
            $table->index('vehicle_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
