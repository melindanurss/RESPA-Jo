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
        // Tambahkan kolom tekanan_udara ke tabel server_monitorings
        if (Schema::hasTable('server_monitorings')) {
            Schema::table('server_monitorings', function (Blueprint $table) {
                if (!Schema::hasColumn('server_monitorings', 'tekanan_udara')) {
                    $table->float('tekanan_udara')->nullable()->after('kelembapan');
                }
                if (!Schema::hasColumn('server_monitorings', 'status_suhu')) {
                    $table->string('status_suhu')->default('Normal')->after('tekanan_udara');
                }
                if (!Schema::hasColumn('server_monitorings', 'status_kelembaban')) {
                    $table->string('status_kelembaban')->default('Normal')->after('status_suhu');
                }
            });
        }
        
        // Buat index untuk performa query
        if (Schema::hasTable('server_monitorings')) {
            Schema::table('server_monitorings', function (Blueprint $table) {
                $table->index('tekanan_udara');
                $table->index(['suhu', 'kelembapan']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('server_monitorings')) {
            Schema::table('server_monitorings', function (Blueprint $table) {
                $table->dropColumn(['tekanan_udara', 'status_suhu', 'status_kelembaban']);
            });
        }
    }
};