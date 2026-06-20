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
        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_faq_category_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('question', 500);
            $table->text('answer');
            $table->string('status')->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('cms_faq_category_id');
            $table->index('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_faqs');
    }
};
