<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Snapshot histori perubahan konten, polymorphic. Untuk rollback & lihat versi lama.
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->morphs('revisionable');      // revisionable_type, revisionable_id
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('data');                  // snapshot lengkap kolom saat itu
            $table->string('note')->nullable();    // catatan opsional saat menyimpan revisi ("Perbaiki typo")
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
