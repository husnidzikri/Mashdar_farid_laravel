<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('task_submissions', 'link_submission')) {
            Schema::table('task_submissions', function (Blueprint $table) {
                $table->string('link_submission')->nullable();
            });
        }

        if (Schema::hasColumn('task_submissions', 'work_link')) {
            DB::table('task_submissions')
                ->whereNull('link_submission')
                ->whereNotNull('work_link')
                ->update(['link_submission' => DB::raw('work_link')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('task_submissions', 'link_submission')) {
            Schema::table('task_submissions', function (Blueprint $table) {
                $table->dropColumn('link_submission');
            });
        }
    }
};