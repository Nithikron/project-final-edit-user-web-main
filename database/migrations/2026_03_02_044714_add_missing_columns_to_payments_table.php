<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // เพิ่ม column ที่จำเป็น (ยกเว้น status ที่มีอยู่แล้ว)
            if (!Schema::hasColumn('payments', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('payments', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }
            if (!Schema::hasColumn('payments', 'customer_phone')) {
                $table->string('customer_phone')->nullable();
            }
            if (!Schema::hasColumn('payments', 'room_name')) {
                $table->string('room_name')->nullable();
            }
            if (!Schema::hasColumn('payments', 'room_type')) {
                $table->string('room_type')->nullable();
            }
            if (!Schema::hasColumn('payments', 'check_in_date')) {
                $table->date('check_in_date')->nullable();
            }
            if (!Schema::hasColumn('payments', 'check_out_date')) {
                $table->date('check_out_date')->nullable();
            }
            if (!Schema::hasColumn('payments', 'payment_qr')) {
                $table->string('payment_qr')->nullable();
            }
            if (!Schema::hasColumn('payments', 'payment_confirmed_at')) {
                $table->timestamp('payment_confirmed_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // ลบ column ที่เพิ่ม
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_phone',
                'room_name',
                'room_type',
                'check_in_date',
                'check_out_date',
                'payment_qr',
                'payment_confirmed_at',
                'status'
            ]);
        });
    }
};
