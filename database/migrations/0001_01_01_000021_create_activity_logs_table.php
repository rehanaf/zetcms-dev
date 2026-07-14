<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Audit trail: siapa melakukan apa terhadap konten apa.
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');              // created, updated, deleted, published, restored, dll
            $table->nullableMorphs('subject');     // subject_type, subject_id (nullable: mis. login/logout tanpa subject)
            $table->json('properties')->nullable(); // detail perubahan (before/after) atau metadata lain
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
