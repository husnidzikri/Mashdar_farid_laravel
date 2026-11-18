<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('attendance_photo')->nullable()->after('check_out_at');
            $table->decimal('latitude', 10, 7)->nullable()->after('attendance_photo');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('check_out_photo')->nullable()->after('longitude');
            $table->decimal('check_out_latitude', 10, 7)->nullable()->after('check_out_photo');
            $table->decimal('check_out_longitude', 10, 7)->nullable()->after('check_out_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_photo',
                'latitude',
                'longitude',
                'check_out_photo',
                'check_out_latitude',
                'check_out_longitude',
            ]);
        });
    }
};
