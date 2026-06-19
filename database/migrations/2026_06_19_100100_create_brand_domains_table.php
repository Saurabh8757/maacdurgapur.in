<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_domains', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('hostname', 255)->unique();
            $table->string('scheme', 10)->default('https');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_preview')->default(false);
            $table->boolean('redirect_to_primary')->default(false);
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->index(['brand_id', 'status']);
            $table->index(['brand_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_domains');
    }
};
