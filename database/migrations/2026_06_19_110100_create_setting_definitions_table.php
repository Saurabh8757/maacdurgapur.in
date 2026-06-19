<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_definitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('setting_group_id')
                ->constrained('setting_groups')
                ->restrictOnDelete();

            $table->string('key', 190)->unique();
            $table->string('name', 190);
            $table->text('description')->nullable();
            $table->string('data_type', 50)->index();
            $table->string('input_type', 50);
            $table->json('default_value')->nullable();
            $table->json('validation_rules')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_translatable')->default(false);
            $table->boolean('is_brand_overridable')->default(true);
            $table->boolean('is_sensitive')->default(false);
            $table->boolean('is_public')->default(true);
            $table->boolean('requires_publish')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 30)->default('inactive')->index();
            $table->timestamps();

            $table->index(['setting_group_id', 'status', 'sort_order'], 'setting_definitions_group_status_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_definitions');
    }
};
