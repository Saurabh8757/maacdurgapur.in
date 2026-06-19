<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('media_asset_id')
                ->constrained('media_assets')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('usable_type', 100);
            $table->unsignedBigInteger('usable_id');
            $table->string('collection', 100);
            $table->string('role', 100)->nullable();
            $table->string('locale', 10)->default('');
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('context')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                [
                    'media_asset_id',
                    'usable_type',
                    'usable_id',
                    'collection',
                    'locale',
                    'sort_order',
                ],
                'media_usages_asset_usable_collection_locale_order_unique'
            );
            $table->index(
                ['usable_type', 'usable_id', 'collection'],
                'media_usages_usable_collection_index'
            );
            $table->index('media_asset_id');
            $table->index(['brand_id', 'collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_usages');
    }
};
