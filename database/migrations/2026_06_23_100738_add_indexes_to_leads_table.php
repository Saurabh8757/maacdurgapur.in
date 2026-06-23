<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->index('status');
            $table->index('phone');
            $table->index('created_at');
            // Note: brand_id is already indexed because it has a foreign key constraint.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['created_at']);
        });
    }
};
