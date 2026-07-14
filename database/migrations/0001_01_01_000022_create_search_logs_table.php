<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Log pencarian pengunjung di situs -> untuk temukan celah konten ("dicari tapi tidak ada")
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->unsignedInteger('results_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable(); // untuk guest, dari cookie/session
            $table->timestamp('created_at')->useCurrent();

            $table->index('query');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
