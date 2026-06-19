<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('scope_key', 64);
            $table->string('status', 30)->default('pending')->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('reason')->nullable();

            $table->foreignId('revoked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->timestamps();

            $table->unique(
                ['user_id', 'role_id', 'scope_key', 'status'],
                'user_roles_user_role_scope_status_unique'
            );
            $table->index(['user_id', 'status', 'starts_at', 'expires_at']);
            $table->index(['brand_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
