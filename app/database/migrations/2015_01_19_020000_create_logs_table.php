<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

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
			$table->foreign('bucket_id')->references('id')->on('buckets')->onDelete('cascade');;
			$table->string('message');
			$table->string('name');
			$table->string('url');
			$table->string('useragent');
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
