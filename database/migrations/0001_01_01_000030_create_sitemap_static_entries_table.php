<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Untuk URL yang bukan berasal dari model Eloquent (mis. halaman hasil generate dari API luar)
    // tapi tetap perlu masuk sitemap.xml
    public function up(): void
    {
        Schema::create('sitemap_static_entries', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->string('change_freq')->default('monthly');
            $table->timestamp('last_modified')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sitemap_static_entries');
    }
};
