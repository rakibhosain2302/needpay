<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('profile_photo')->nullable()->after('password');
            $table->enum('account_type', ['customer', 'driver', 'admin'])->default('customer')->after('profile_photo');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])->default('active')->after('account_type');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes();

            $table->index('account_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['account_type']);
            $table->dropIndex(['status']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone',
                'profile_photo',
                'account_type',
                'status',
                'phone_verified_at',
                'last_login_at',
            ]);
        });
    }
};
