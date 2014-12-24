<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('guestid')->nullable();
            $table->string('name');
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('user_pending', function ($table) {
            $table->increments('id');
            $table->string('hash');
            $table->string('email');
            $table->string('name');
            $table->string('password');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('catalog_items', function ($table) {
            $table->increments('id');
            $table->bigInteger('parent_id')->nullable();
            $table->string('title');
            $table->integer('price');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('orders', function ($table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->integer('total');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });

        Schema::create('order_items', function ($table) {
            $table->increments('id');
            $table->bigInteger('order_id');
            $table->string('catalog_item_id');
            $table->integer('price');
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
        Schema::drop('users');
        Schema::drop('user_pending');
        Schema::drop('orders');
        Schema::drop('order_items');
        Schema::drop('catalog_items');
    }

}
