<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, perlu drop dan recreate enum
        Schema::table('devices', function (Blueprint $table) {
            $table->string('device_type_temp')->nullable();
        });
        
        DB::statement("UPDATE devices SET device_type_temp = device_type");
        
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('device_type');
        });
        
        Schema::table('devices', function (Blueprint $table) {
            $table->enum('device_type', ['suhu', 'infus', 'bme280'])->default('suhu')->after('id');
        });
        
        DB::statement("UPDATE devices SET device_type = device_type_temp");
        
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('device_type_temp');
        });
    }

    public function down(): void
    {
        // Rollback logic jika diperlukan
    }
};