<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email');
			$table->string('name');
			$table->string('password');
			$table->string('remember_token', 100);
			$table->dateTime('updated_at');
			$table->dateTime('created_at');
		});

		Schema::create('users_confirm', function($table)
		{
			$table->increments('id');
			$table->string('user_id');
			$table->string('hash');
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
	}

}
