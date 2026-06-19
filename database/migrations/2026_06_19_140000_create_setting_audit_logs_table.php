<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->uuid('brand_uuid')->nullable()->index();

            $table->foreignId('setting_definition_id')
                ->nullable()
                ->constrained('setting_definitions')
                ->nullOnDelete();

            $table->foreignId('setting_value_id')
                ->nullable()
                ->constrained('setting_values')
                ->nullOnDelete();

            $table->foreignId('settings_publication_id')
                ->nullable()
                ->constrained('settings_publications')
                ->nullOnDelete();

            $table->string('scope_key', 64)->index();
            $table->string('locale', 10)->default('en')->index();
            $table->string('event', 100)->index();
            $table->json('before_value')->nullable();
            $table->json('after_value')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['scope_key', 'locale', 'created_at'],
                'setting_audit_logs_scope_locale_created_index'
            );
            $table->index(
                ['setting_definition_id', 'created_at'],
                'setting_audit_logs_definition_created_index'
            );
            $table->index(
                ['setting_value_id', 'created_at'],
                'setting_audit_logs_value_created_index'
            );
            $table->index(
                ['settings_publication_id', 'created_at'],
                'setting_audit_logs_publication_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_audit_logs');
    }
};
