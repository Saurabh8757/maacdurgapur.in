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
        Schema::table('lead_form_fields', function (Blueprint $table) {
            $table->string('form_type')->default('hero')->after('brand_id')->comment('hero or global_modal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_form_fields', function (Blueprint $table) {
            $table->dropColumn('form_type');
        });
    }
};
