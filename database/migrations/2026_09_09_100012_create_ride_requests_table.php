<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete();
            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 7);
            $table->decimal('pickup_longitude', 10, 7);
            $table->string('destination_address');
            $table->decimal('destination_latitude', 10, 7);
            $table->decimal('destination_longitude', 10, 7);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->unsignedInteger('estimated_duration_minutes')->nullable();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->restrictOnDelete();
            $table->unsignedTinyInteger('passenger_count')->default(1);
            $table->decimal('customer_offered_fare', 12, 2)->nullable();
            $table->text('special_instruction')->nullable();
            $table->enum('status', [
                'pending', 'bidding', 'bid_accepted', 'driver_arriving',
                'in_progress', 'completed', 'cancelled', 'expired',
            ])->default('pending');
            $table->timestamp('bid_started_at')->nullable();
            $table->timestamp('bid_expires_at')->nullable();
            // Foreign key to ride_bids added in a later migration once that table exists.
            $table->unsignedBigInteger('selected_bid_id')->nullable();
            $table->foreignId('selected_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('vehicle_type_id');
            $table->index('status');
            $table->index('selected_driver_id');
            $table->index('bid_expires_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
