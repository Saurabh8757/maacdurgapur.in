<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_value_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('setting_value_id')
                ->constrained('setting_values')
                ->restrictOnDelete();

            $table->unsignedInteger('version_number');
            $table->json('value')->nullable();
            $table->string('status', 30);
            $table->string('change_summary', 500)->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['setting_value_id', 'version_number']);
            $table->index(['created_by', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_value_versions');
    }
};
