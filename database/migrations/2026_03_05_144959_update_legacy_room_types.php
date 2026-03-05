<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * แปลงห้องเก่าให้เป็น type ใหม่
     * เดี่ยว + แอร์ -> air_single
     * คู่ + แอร์ -> air_double
     * เดี่ยว + พัดลม -> fan_single
     * คู่ + พัดลม -> fan_double
     */
    public function up(): void
    {
        // Update rooms with legacy types
        // air_single: เดี่ยว + แอร์
        DB::table('rooms')
            ->where('type', 'เดี่ยว')
            ->where('facility', 'like', '%แอร์%')
            ->update(['type' => 'air_single']);

        // air_double: คู่ + แอร์
        DB::table('rooms')
            ->where('type', 'คู่')
            ->where('facility', 'like', '%แอร์%')
            ->update(['type' => 'air_double']);

        // fan_single: เดี่ยว + พัดลม
        DB::table('rooms')
            ->where('type', 'เดี่ยว')
            ->where('facility', 'like', '%พัดลม%')
            ->update(['type' => 'fan_single']);

        // fan_double: คู่ + พัดลม
        DB::table('rooms')
            ->where('type', 'คู่')
            ->where('facility', 'like', '%พัดลม%')
            ->update(['type' => 'fan_double']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to legacy types (optional)
        // This would be complex since we lose the mapping, so we'll skip it
    }
};

