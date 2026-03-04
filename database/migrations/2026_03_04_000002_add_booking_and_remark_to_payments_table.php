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
            // Add booking_id foreign key
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            // Add remark column
            $table->text('remark')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeignKeyConstraints();
            $table->dropColumn(['booking_id', 'remark']);
        });
    }
};
