<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('task_submissions', 'supervisor_feedback')) {
            Schema::table('task_submissions', function (Blueprint $table) {
                $table->text('supervisor_feedback')->nullable();
            });
        }

        if (Schema::hasColumn('task_submissions', 'supervisor_comment')) {
            DB::table('task_submissions')
                ->whereNull('supervisor_feedback')
                ->whereNotNull('supervisor_comment')
                ->update(['supervisor_feedback' => DB::raw('supervisor_comment')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('task_submissions', 'supervisor_feedback')) {
            Schema::table('task_submissions', function (Blueprint $table) {
                $table->dropColumn('supervisor_feedback');
            });
        }
    }
};