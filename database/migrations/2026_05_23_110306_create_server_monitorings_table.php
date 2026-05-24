<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('device_key');
            $table->string('ruang')->nullable();
            $table->float('suhu');
            $table->float('kelembapan');
            $table->string('status')->default('Normal');
            $table->string('last_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_monitorings');
    }
};