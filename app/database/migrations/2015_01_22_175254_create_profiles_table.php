<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::create('profiles',function($table){
			$table->increments('id');
			$table->integer('bucket_id')->unsigned();
			$table
				->foreign('bucket_id')
				->references('id')
				->on('buckets')
				->onDelete('cascade');
			$table->integer('reference_id')->unsigned()->nullable();
			$table
				->foreign('reference_id')
				->references('id')
				->on('references');
			$table->string('summary')->index();
			$table->string('alias')->nullable();
			$table->string('message');
			$table->string('name');
			$table->timestamps();
		});
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::drop('profiles');
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}
