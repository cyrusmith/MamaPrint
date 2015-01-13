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

            $table->boolean('active')->default(true);
            $table->string('old_price')->nullable();
            $table->string('info_age')->nullable();
            $table->string('info_targets')->nullable();
            $table->string('info_level')->nullable();
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

        Schema::create('attachments', function ($table) {
            $table->increments('id');
            $table->enum('model', array(Attachment::MODEL_ARTICLE, Attachment::MODEL_CATALOGITEM));
            $table->bigInteger('model_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('mime');
            $table->string('extension');
            $table->bigInteger('size');
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
