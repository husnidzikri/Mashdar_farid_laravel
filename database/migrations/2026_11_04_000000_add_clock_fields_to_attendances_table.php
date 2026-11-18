<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('status')->default('Hadir')->change();
            $table->time('check_in_at')->nullable()->after('keterangan');
            $table->time('check_out_at')->nullable()->after('check_in_at');
            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        DB::table('attendances')->where('status', 'Terlambat')->update(['status' => 'Hadir']);

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'date']);
            $table->dropColumn(['check_in_at', 'check_out_at']);
            $table->enum('status', ['Hadir', 'Izin', 'Sakit'])->default('Hadir')->change();
        });
    }
};
