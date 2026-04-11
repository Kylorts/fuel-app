<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->enum('role', [
                'staf_pembeli',
                'manajer_pembeli',
                'admin_penjualan',
                'admin_depo',
                'petugas_spbu',
            ])->default('staf_pembeli')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'role']);
        });
    }
};
