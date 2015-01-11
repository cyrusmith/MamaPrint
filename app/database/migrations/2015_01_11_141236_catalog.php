<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Catalog extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalog_items', function ($table) {
            $table->dropColumn(array('asset_extension', 'asset_name'));
            $table->string('old_price');
            $table->string('info_age');
            $table->string('info_targets');
            $table->string('info_level');
        });

        Schema::create('tags', function ($table) {
            $table->increments('id');
            $table->string('tag');
        });

        Schema::create('tag_catalog_item', function ($table) {
            $table->increments('id');
            $table->bigInteger('tag_id');
            $table->bigInteger('catalog_item_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
