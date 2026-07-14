<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Form builder: kontak, newsletter, pendaftaran, dll, tanpa perlu kode baru per form
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('success_message')->nullable();
            $table->string('notification_email')->nullable(); // kirim email notif ke sini tiap ada submission baru
            $table->json('settings')->nullable();               // redirect_url, recaptcha, dll
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
