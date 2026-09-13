<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY account_type ENUM('customer', 'driver', 'admin', 'passenger') NOT NULL DEFAULT 'customer'");
        DB::table('users')->where('account_type', 'customer')->update(['account_type' => 'passenger']);
        DB::statement("ALTER TABLE users MODIFY account_type ENUM('passenger', 'driver', 'admin') NOT NULL DEFAULT 'passenger'");

        DB::table('roles')->where('slug', 'customer')->update(['slug' => 'passenger', 'name' => 'Passenger']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY account_type ENUM('passenger', 'driver', 'admin', 'customer') NOT NULL DEFAULT 'passenger'");
        DB::table('users')->where('account_type', 'passenger')->update(['account_type' => 'customer']);
        DB::statement("ALTER TABLE users MODIFY account_type ENUM('customer', 'driver', 'admin') NOT NULL DEFAULT 'customer'");

        DB::table('roles')->where('slug', 'passenger')->update(['slug' => 'customer', 'name' => 'Customer']);
    }
};
