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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();

            // --- Profile Section ---
            $table->string('profile_name')->default('My Linktree');
            $table->text('profile_bio')->nullable();
            $table->string('avatar_url')->nullable();

            // --- Theme & Appearance Configuration (JSON) ---
            // Isinya nanti:
            // - background_type (color, gradient, image)
            // - background_value (hex code, css gradient, image path)
            // - button_style (rounded, square, pill, outline, soft-shadow)
            // - font_family
            // - global_text_color
            // - global_button_color
            $table->json('theme_config')->nullable();

            // --- Social Media Icons (Footer) ---
            // Isinya array object: { platform: 'instagram', url: '...', icon: '...' }
            $table->json('social_links')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
