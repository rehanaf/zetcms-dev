<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->string('billing_period')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('button_text')->default('Get Started');
            $table->string('button_url')->default('#');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
