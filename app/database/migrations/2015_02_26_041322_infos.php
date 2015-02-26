<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Infos extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('info_ages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
        });

        Schema::create('info_ages_catalog_item', function (Blueprint $table) {
            $table->bigInteger('catalog_item_id');
            $table->bigInteger('info_age_id');
        });

        Schema::create('info_develop_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
        });

        Schema::create('info_develop_targets_catalog_item', function (Blueprint $table) {
            $table->bigInteger('catalog_item_id');
            $table->bigInteger('info_develop_target_id');
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
