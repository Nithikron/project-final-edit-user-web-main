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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name_room');
            $table->string('type'); // เดี่ยว, คู่
            $table->json('facility')->nullable(); // ["แอร์","wifi","ทีวี"]
            $table->decimal('price', 10, 2);
            // $table->tinyInteger('status')->default(0);
            $table->enum('status', ['available', 'reserved'])->default('available');
            // 0 = ว่าง, 1 = ไม่ว่าง
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};