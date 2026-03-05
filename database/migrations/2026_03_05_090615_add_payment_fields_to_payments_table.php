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
            $table->string('payment_method')->nullable()->after('remark');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->timestamp('payment_date')->nullable()->after('payment_status');
            $table->string('slip_image')->nullable()->after('payment_date');
            $table->text('notes')->nullable()->after('slip_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_date', 'slip_image', 'notes']);
        });
    }
};
