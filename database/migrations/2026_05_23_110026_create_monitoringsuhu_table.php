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
        Schema::create('monitoringsuhu', function (Blueprint $table) {
        $table->id();
        $table->float('suhu'); // suhu dalam derajat Celsius
        $table->float('kelembaban'); // kelembaban dalam persen RH
        $table->string('status_suhu'); // contoh: Tinggi, Normal, Sedang
        $table->string('status_kelembaban'); // contoh: Ideal, Rendah, Tinggi
        $table->string('keterangan')->nullable(); // contoh: Perlu pengecekan AC
        $table->timestamps(); // created_at & updated_at
     });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoringsuhu');
    }
};