<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('lineage_uuid');
            $table->unsignedInteger('version_number')->default(1);

            $table->foreignId('previous_version_id')
                ->nullable()
                ->constrained('media_assets')
                ->restrictOnDelete();

            $table->boolean('is_current')->default(true);

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->foreignId('media_folder_id')
                ->nullable()
                ->constrained('media_folders')
                ->nullOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('storage_disk', 100);
            $table->string('storage_key', 1024);
            $table->string('original_filename', 255);
            $table->string('display_name', 255);
            $table->string('extension', 20);
            $table->string('mime_type', 150);
            $table->string('media_type', 30)->index();
            $table->string('visibility', 30)->default('private');
            $table->string('security_classification', 30)->default('internal');
            $table->string('status', 30)->default('uploading')->index();
            $table->unsignedBigInteger('size_bytes');
            $table->char('checksum_sha256', 64);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->unsignedInteger('page_count')->nullable();
            $table->string('alt_text', 500)->nullable();
            $table->text('caption')->nullable();
            $table->string('credit', 500)->nullable();
            $table->string('copyright', 500)->nullable();
            $table->decimal('focal_x', 6, 5)->nullable();
            $table->decimal('focal_y', 6, 5)->nullable();
            $table->json('metadata')->nullable();
            $table->text('processing_error')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['lineage_uuid', 'version_number']);
            $table->unique(
                ['storage_disk', 'storage_key'],
                'media_assets_storage_disk_key_unique'
            );
            $table->index(['brand_id', 'status', 'media_type']);
            $table->index(['checksum_sha256', 'size_bytes']);
            $table->index(['visibility', 'security_classification']);
            $table->index(['lineage_uuid', 'is_current']);
            $table->index(['media_folder_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
