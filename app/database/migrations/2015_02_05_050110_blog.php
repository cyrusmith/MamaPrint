<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Blog extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('urlpath');
            $table->dateTime('publish_date');
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
        Schema::drop('articles');
    }

}
