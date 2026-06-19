<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('setting_definition_id')
                ->constrained('setting_definitions')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('scope_key', 64);
            $table->string('locale', 10)->default('en');
            $table->json('value')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('published_at')->nullable();

            $table->foreignId('published_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['setting_definition_id', 'scope_key', 'locale', 'status'],
                'setting_values_definition_scope_locale_status_unique'
            );
            $table->index(['brand_id', 'status']);
            $table->index(
                ['setting_definition_id', 'scope_key', 'locale'],
                'setting_values_definition_scope_locale_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_values');
    }
};
