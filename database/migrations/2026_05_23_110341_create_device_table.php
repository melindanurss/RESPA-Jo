<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_key')->unique();
            $table->enum('device_type', ['suhu', 'infus'])->default('suhu');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active')->nullable();
            $table->string('last_activity')->nullable();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->index(['device_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};