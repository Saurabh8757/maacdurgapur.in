<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('parent_role_id')
                ->nullable()
                ->constrained('roles')
                ->restrictOnDelete();

            $table->string('code', 100)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('scope_type', 30)->index();
            $table->string('risk_level', 20)->default('medium')->index();
            $table->boolean('is_system')->default(true);
            $table->boolean('is_assignable')->default(true);
            $table->string('status', 30)->default('active')->index();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
