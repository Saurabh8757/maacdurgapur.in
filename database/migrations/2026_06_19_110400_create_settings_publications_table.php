<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_publications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('scope_key', 64)->index();
            $table->string('locale', 10)->default('en');
            $table->string('status', 30)->default('pending')->index();
            $table->text('change_summary')->nullable();

            $table->foreignId('published_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('published_at')->nullable();

            $table->foreignId('rolled_back_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('rolled_back_at')->nullable();
            $table->timestamps();

            $table->index(['brand_id', 'status']);
            $table->index(['scope_key', 'locale', 'status'], 'settings_publications_scope_locale_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_publications');
    }
};
