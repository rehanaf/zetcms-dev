<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // "Default Theme"
            $table->string('slug')->unique();         // "default" -> nama folder resources/views/themes/default
            $table->text('description')->nullable();
            $table->string('screenshot')->nullable(); // preview thumbnail theme
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
