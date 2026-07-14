<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Polymorphic: bisa dipakai oleh posts, pages, categories, dll.
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seo_metable'); // seo_metable_id, seo_metable_type

            // Meta dasar
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('locale', 10)->default('id');       // locale konten ini: id, en, dst
            $table->uuid('hreflang_group')->nullable();          // menghubungkan versi bahasa yang sama (id & en dari 1 konten)

            // Robots
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);

            // Open Graph (Facebook, LinkedIn, dll)
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');

            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->string('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();

            // Structured data (Schema.org) & sitemap
            $table->json('schema_markup')->nullable();
            $table->boolean('sitemap_include')->default(true);
            $table->decimal('sitemap_priority', 2, 1)->default(0.5);
            $table->string('sitemap_change_freq')->default('weekly');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
