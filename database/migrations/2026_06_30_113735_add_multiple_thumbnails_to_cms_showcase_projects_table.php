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
        Schema::table('cms_showcase_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('thumbnail_media_id_2')->nullable()->after('thumbnail_media_id');
            $table->unsignedBigInteger('thumbnail_media_id_3')->nullable()->after('thumbnail_media_id_2');
            $table->unsignedBigInteger('thumbnail_media_id_4')->nullable()->after('thumbnail_media_id_3');
            $table->unsignedBigInteger('thumbnail_media_id_5')->nullable()->after('thumbnail_media_id_4');

            $table->foreign('thumbnail_media_id_2')->references('id')->on('media_assets')->onDelete('set null');
            $table->foreign('thumbnail_media_id_3')->references('id')->on('media_assets')->onDelete('set null');
            $table->foreign('thumbnail_media_id_4')->references('id')->on('media_assets')->onDelete('set null');
            $table->foreign('thumbnail_media_id_5')->references('id')->on('media_assets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_showcase_projects', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_media_id_2']);
            $table->dropForeign(['thumbnail_media_id_3']);
            $table->dropForeign(['thumbnail_media_id_4']);
            $table->dropForeign(['thumbnail_media_id_5']);

            $table->dropColumn([
                'thumbnail_media_id_2',
                'thumbnail_media_id_3',
                'thumbnail_media_id_4',
                'thumbnail_media_id_5',
            ]);
        });
    }
};
