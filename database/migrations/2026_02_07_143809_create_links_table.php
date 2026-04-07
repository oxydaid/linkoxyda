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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Nama tombol (misal: "Join Discord")
            $table->string('url'); // Link tujuan
            $table->enum('display_type', ['icon', 'image'])->default('icon'); // Tipe tampilan: icon atau gambar
            $table->string('icon')->nullable(); // Nama icon (FontAwesome/BladeIcons string)
            $table->string('image_url')->nullable(); // Gambar link (bisa menggunakan icon atau gambar)

            // Kustomisasi per tombol (opsional, jika ingin beda dari tema global)
            $table->string('text_color')->nullable();
            $table->string('bg_color')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('open_new_tab')->default(true); // Target _blank
            $table->integer('sort_order')->default(0); // Untuk urutan drag-and-drop
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
