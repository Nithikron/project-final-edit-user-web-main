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
            // Change status column to be text/varchar to allow any value
            // First drop the enum column then add it back as varchar
            $table->dropColumn('status');
            $table->string('status')->default('pending')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', ['paid', 'pending'])->default('paid');
        });
    }
};

