<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Searchables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('type');
            $table->integer('weight');
        });

        Schema::table('tag_catalog_item', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->renameColumn('catalog_item_id', 'taggable_id');
            $table->string('taggable_type');
        });

        Schema::rename('tag_catalog_item', 'taggables');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('taggables', 'tag_catalog_item');

        Schema::table('tag_catalog_item', function (Blueprint $table) {
            $table->increments('id');
            $table->renameColumn('taggable_id', 'catalog_item_id');
            $table->dropColumn('taggable_type');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('weight');
        });

    }

}
