<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCityAndStateFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users',function($table){
			$table->string('state')->nullable()->after('country_id');
			$table->string('city')->after('country_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users',function($table){
			$table->dropColumn('city');
			$table->dropColumn('state');
		});
	}

}
