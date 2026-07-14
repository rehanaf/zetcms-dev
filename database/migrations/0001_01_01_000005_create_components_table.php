<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Partial / reusable block: header, footer, sidebar, hero, cta, dsb.
    // Satu "type" bisa punya banyak "variant" (mis. hero/variant1, hero/variant2)
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
            $table->string('name');                    // "Hero - Dengan Gambar"
            $table->string('slug')->unique();           // "hero-variant2"

            $table->enum('type', [
                'header', 'footer', 'sidebar', 'hero',
                'pricing', 'testimonial', 'section', 'widget', 'cta', 'custom',
            ])->default('section');

            // Nama file variant di dalam folder type, mis. "variant1" -> partials/hero/variant1.blade.php
            $table->string('variant')->default('variant1');

            // Opsional: override manual path Blade kalau tidak mengikuti pola type/variant
            $table->string('view_path')->nullable();

            $table->string('thumbnail')->nullable();     // preview gambar untuk dropdown pemilihan di admin
            $table->longText('content')->nullable();     // konten default (bisa HTML/markdown)
            $table->json('settings')->nullable();          // pengaturan default (warna, opsi, dll)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['theme_id', 'type', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
