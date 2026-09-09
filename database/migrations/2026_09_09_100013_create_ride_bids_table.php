<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_request_id')->constrained('ride_requests')->restrictOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->decimal('offered_amount', 12, 2);
            $table->unsignedInteger('estimated_arrival_minutes')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', [
                'pending', 'countered', 'accepted', 'rejected',
                'withdrawn', 'expired', 'closed',
            ])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('parent_bid_id')->nullable()->constrained('ride_bids')->restrictOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->timestamps();

            $table->index('ride_request_id');
            $table->index('driver_id');
            $table->index('vehicle_id');
            $table->index('status');
            $table->index('expires_at');
            $table->index('parent_bid_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_bids');
    }
};
