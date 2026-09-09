<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bid_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_bid_id')->constrained('ride_bids')->restrictOnDelete();
            $table->enum('actor_type', ['customer', 'driver', 'system', 'admin']);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->enum('action', [
                'created', 'countered', 'accepted', 'rejected',
                'withdrawn', 'expired', 'closed',
            ]);
            $table->decimal('old_amount', 12, 2)->nullable();
            $table->decimal('new_amount', 12, 2)->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('ride_bid_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bid_histories');
    }
};
