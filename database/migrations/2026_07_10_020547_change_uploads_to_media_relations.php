<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign keys
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('featured_image_id')->nullable()->after('featured_image')->constrained('media')->nullOnDelete();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('featured_image_id')->nullable()->after('featured_image')->constrained('media')->nullOnDelete();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreignId('avatar_id')->nullable()->after('avatar')->constrained('media')->nullOnDelete();
        });

        Schema::table('pricings', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->after('image')->constrained('media')->nullOnDelete();
        });

        // Drop old string columns
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('featured_image');
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('featured_image');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
        Schema::table('pricings', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }

    public function down(): void
    {
        // Re-add old string columns
        Schema::table('posts', function (Blueprint $table) {
            $table->string('featured_image')->nullable();
            $table->dropForeign(['featured_image_id']);
            $table->dropColumn('featured_image_id');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('featured_image')->nullable();
            $table->dropForeign(['featured_image_id']);
            $table->dropColumn('featured_image_id');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->dropForeign(['avatar_id']);
            $table->dropColumn('avatar_id');
        });

        Schema::table('pricings', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });
    }
};
