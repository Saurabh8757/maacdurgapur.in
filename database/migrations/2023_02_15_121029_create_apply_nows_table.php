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
        Schema::create('apply_nows', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('c_location')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('dob')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('c_salary')->nullable();
            $table->string('functional_area')->nullable();
            $table->string('current_industry')->nullable();
            $table->string('preferred_area')->nullable();
            $table->string('skill')->nullable();
            $table->string('basic')->nullable();
            $table->string('degree1')->nullable();
            $table->string('degree2')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->string('subject')->nullable();
            $table->string('current_company')->nullable();
            $table->string('file')->nullable();
            $table->longText('msg')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apply_nows');
    }
};
