<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('media_folders')
                ->restrictOnDelete();

            $table->string('scope_key', 64);
            $table->string('parent_scope_key', 64)->default('root');
            $table->string('name', 190);
            $table->string('slug', 190);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 30)->default('active')->index();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['scope_key', 'parent_scope_key', 'slug'],
                'media_folders_scope_parent_slug_unique'
            );
            $table->index(['brand_id', 'status']);
            $table->index(['parent_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_folders');
    }
};
