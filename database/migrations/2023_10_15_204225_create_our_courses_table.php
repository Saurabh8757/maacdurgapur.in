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
        Schema::create('our_courses', function (Blueprint $table) {
            $table->id();
            $table->string('courses_slug')->nullable();
            $table->string('name')->nullable();
            $table->longText('desc')->nullable();
            $table->string('image')->nullable();
            //$table->integer('views')->nullable();
            $table->enum('status',['Active','InActive'])->default('Active');

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
        Schema::dropIfExists('our_courses');
    }
};
