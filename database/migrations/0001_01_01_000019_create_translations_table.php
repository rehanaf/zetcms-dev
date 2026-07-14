<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Terjemahan per-field, polymorphic: bisa dipakai posts, pages, categories, dll.
    // Field asli di tabel utama tetap jadi bahasa default (fallback).
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');      // translatable_type, translatable_id
            $table->string('locale', 10);          // "en", "id", "ja", dst
            $table->string('field');                // nama kolom yang diterjemahkan: "title", "content", dsb
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'translations_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
