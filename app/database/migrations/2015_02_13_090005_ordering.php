<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ordering extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('catalog_items', function (Blueprint $table) {
			$table->integer('weight')->default(0);
		});
		Schema::table('articles', function (Blueprint $table) {
			$table->boolean('isblog')->default(true);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('isblog');
        });
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
	}

}
