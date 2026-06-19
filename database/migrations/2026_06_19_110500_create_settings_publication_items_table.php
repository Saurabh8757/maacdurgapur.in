<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_publication_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('settings_publication_id')
                ->constrained('settings_publications')
                ->restrictOnDelete();

            $table->foreignId('setting_value_id')
                ->constrained('setting_values')
                ->restrictOnDelete();

            $table->foreignId('setting_value_version_id')
                ->constrained('setting_value_versions')
                ->restrictOnDelete();

            $table->foreignId('previous_version_id')
                ->nullable()
                ->constrained('setting_value_versions')
                ->restrictOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->unique(
                ['settings_publication_id', 'setting_value_id'],
                'settings_publication_items_publication_value_unique'
            );
            $table->index('setting_value_version_id');
            $table->index('previous_version_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings_publication_items');
    }
};
