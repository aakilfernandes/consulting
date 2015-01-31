<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscriptions',function($table){
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('bucket_id')->unsigned()->index();
			$table->foreign('bucket_id')->references('id')->on('buckets')->onDelete('cascade');
			$table->boolean('profileCreated')->default(true);
			$table->boolean('profileReopened')->default(true);
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
		Schema::drop('subscriptions');
	}

}
