<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Pivot: susunan komponen/partial di dalam sebuah page (page builder)
    public function up(): void
    {
        Schema::create('page_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('component_id')->constrained()->cascadeOnDelete();
            $table->string('region')->default('content'); // header | content | sidebar | footer
            $table->unsignedInteger('order')->default(0);
            $table->json('settings_override')->nullable(); // override settings default komponen
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_components');
    }
};
