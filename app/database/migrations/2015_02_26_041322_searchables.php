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
        //
    }

}
