<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('errors',function($table){
			$table->increments('id');
			$table->integer('bucket_id')->unsigned();
			$table
				->foreign('bucket_id')
				->references('id')
				->on('buckets')
				->onDelete('cascade');
			$table->integer('profile_id')->unsigned();
			$table
				->foreign('profile_id')
				->references('id')
				->on('profiles')
				->onDelete('cascade');
			$table->string('message');
			$table->string('name');
			$table->string('summary')->index();
			$table->string('url');
			$table->string('urlHost')->index();
			$table->string('urlPath')->index()->nullable();
			$table->string('urlQuery')->index()->nullable();
			$table->string('urlFragment')->index()->nullable();
			$table->string('useragent');
			$table->string('browser')->index()->nullable();
			$table->string('os')->index()->nullable();
			$table->string('device')->index()->nullable();
			$table->mediumText('stack');
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
		Schema::drop('errors');
	}

}
