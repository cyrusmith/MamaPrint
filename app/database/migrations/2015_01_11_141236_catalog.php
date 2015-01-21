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
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('galleries', function ($table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('gallery_relations', function ($table) {
            $table->increments('id');
            $table->bigInteger('gallery_id');
            $table->string('gallery_relation_type');
            $table->bigInteger('gallery_relation_id');
        });

        Schema::create('gallery_images', function ($table) {
            $table->increments('id');
            $table->bigInteger('gallery_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->string('mime');
            $table->string('extension');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('carts', function ($table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('cart_items', function ($table) {
            $table->increments('id');
            $table->bigInteger('cart_id');
            $table->bigInteger('catalog_item_id');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('user_catalog_items_access', function ($table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->bigInteger('catalog_item_id');
            $table->bigInteger('order_id');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
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
