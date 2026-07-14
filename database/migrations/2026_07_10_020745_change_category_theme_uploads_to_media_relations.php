<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign keys
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->after('image')->constrained('media')->nullOnDelete();
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->foreignId('screenshot_id')->nullable()->after('screenshot')->constrained('media')->nullOnDelete();
        });

        // Drop old string columns
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn('screenshot');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });

        Schema::table('themes', function (Blueprint $table) {
            $table->string('screenshot')->nullable();
            $table->dropForeign(['screenshot_id']);
            $table->dropColumn('screenshot_id');
        });
    }
};
