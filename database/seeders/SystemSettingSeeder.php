<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'platform_name', 'value' => 'Needpay', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_currency', 'value' => 'BDT', 'type' => 'string', 'group' => 'general'],
            ['key' => 'support_email', 'value' => 'support@needpay.test', 'type' => 'string', 'group' => 'general'],
            ['key' => 'support_phone', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_commission_percentage', 'value' => '15', 'type' => 'decimal', 'group' => 'commission'],
            ['key' => 'bidding_window_seconds', 'value' => '60', 'type' => 'integer', 'group' => 'bidding'],
            ['key' => 'max_bid_counter_offers', 'value' => '3', 'type' => 'integer', 'group' => 'bidding'],
            ['key' => 'driver_document_expiry_alert_days', 'value' => '7', 'type' => 'integer', 'group' => 'driver'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
