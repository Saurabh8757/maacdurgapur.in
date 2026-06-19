<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 190)->unique();
            $table->string('domain', 100)->index();
            $table->string('resource', 100);
            $table->string('action', 100);
            $table->string('name', 190);
            $table->text('description')->nullable();
            $table->string('scope_type', 30)->index();
            $table->string('risk_level', 20)->default('medium')->index();
            $table->boolean('requires_mfa')->default(false);
            $table->boolean('requires_reauthentication')->default(false);
            $table->boolean('is_delegable')->default(false);
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();

            $table->index(['domain', 'resource', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
