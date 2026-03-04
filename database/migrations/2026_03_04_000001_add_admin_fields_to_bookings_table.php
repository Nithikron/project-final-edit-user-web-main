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
        Schema::table('bookings', function (Blueprint $table) {
            // Add columns for admin check-in/check-out/booking system
            $table->string('tenant_name')->nullable()->after('customer_email');
            $table->enum('type', ['booking', 'reserve', 'checkin', 'checkout'])->nullable()->after('status');
            $table->text('notes')->nullable()->after('type');
            $table->timestamp('date')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['tenant_name', 'type', 'notes', 'date']);
        });
    }
};
