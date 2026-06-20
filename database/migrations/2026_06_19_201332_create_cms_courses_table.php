<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->index();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description');
            $table->json('tools_covered')->nullable();
            $table->unsignedBigInteger('thumbnail_media_id')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['brand_id', 'slug']);
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('thumbnail_media_id')->references('id')->on('media_assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_courses');
    }
};
