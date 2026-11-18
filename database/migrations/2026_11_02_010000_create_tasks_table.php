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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Judul tugas
        $table->text('description'); // Deskripsi pengerjaan
        $table->enum('category', ['Konten', 'Coding', 'Design', 'Lainnya'])->default('Coding'); // Kategori tugas
        $table->enum('priority', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
        $table->date('deadline'); // Tenggat waktu
        $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
