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
        Schema::create('cms_showcase_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->index();
            $table->unsignedBigInteger('cms_showcase_category_id')->index();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('student_name', 255);
            $table->text('short_description');
            $table->unsignedBigInteger('thumbnail_media_id')->nullable()->index();
            $table->string('video_url', 500)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['brand_id', 'slug']);
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('cms_showcase_category_id')->references('id')->on('cms_showcase_categories')->onDelete('cascade');
            $table->foreign('thumbnail_media_id')->references('id')->on('media_assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_showcase_projects');
    }
};
