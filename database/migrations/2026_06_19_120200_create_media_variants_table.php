<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_variants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('media_asset_id')
                ->constrained('media_assets')
                ->restrictOnDelete();

            $table->string('name', 100);
            $table->string('storage_disk', 100);
            $table->string('storage_key', 1024);
            $table->string('mime_type', 150);
            $table->string('extension', 20);
            $table->unsignedBigInteger('size_bytes');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->char('checksum_sha256', 64);
            $table->json('processing_parameters')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->timestamps();

            $table->unique(['media_asset_id', 'name']);
            $table->unique(
                ['storage_disk', 'storage_key'],
                'media_variants_storage_disk_key_unique'
            );
            $table->index('checksum_sha256');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_variants');
    }
};
