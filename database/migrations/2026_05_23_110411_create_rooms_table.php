<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique();
            $table->string('room_name');
            $table->string('device_id')->nullable();
            $table->decimal('current_temperature', 5, 2)->nullable();
            $table->decimal('current_humidity', 5, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamp('last_update')->nullable();
            $table->timestamps();
            $table->index(['room_id', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};