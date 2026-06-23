<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('placement_showcases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('student_name');
            $table->string('company_name')->nullable();
            $table->string('designation');
            $table->integer('annual_package'); // Numeric value e.g. 379000
            $table->unsignedBigInteger('student_image_media_id')->nullable();
            $table->unsignedBigInteger('company_logo_media_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('student_image_media_id')->references('id')->on('media_assets')->onDelete('set null');
            $table->foreign('company_logo_media_id')->references('id')->on('media_assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_showcases');
    }
};
