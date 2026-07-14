<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Trafik harian per post -> untuk grafik trend, bukan cuma angka kumulatif di posts.views
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->date('viewed_date');
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();

            $table->unique(['post_id', 'viewed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
