<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Master layout: app.blade.php, landing.blade.php, dll.
    public function up(): void
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
            $table->string('name');                 // "Default Layout"
            $table->string('slug')->unique();        // "default"
            $table->string('view_path');             // "layouts.app" -> resources/views/layouts/app.blade.php
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layouts');
    }
};
