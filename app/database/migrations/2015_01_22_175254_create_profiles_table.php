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
		Schema::create('profiles',function($table){
			$table->increments('id');
			$table->integer('bucket_id')->unsigned()->index();
			$table
				->foreign('bucket_id')
				->references('id')
				->on('buckets')
				->onDelete('cascade');
			$table->string('status_id')->default('default')->index();
			$table
				->foreign('status_id')
				->references('id')
				->on('statuses');
			$table->integer('reference_id')->unsigned()->index()->nullable();
			$table
				->foreign('reference_id')
				->references('id')
				->on('references');
			$table->boolean('isCollapsed')->default(0);
			$table->string('summary')->index();
			$table->string('alias')->nullable();
			$table->string('message');
			$table->string('name');
			$table->timestamps();
		});
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
