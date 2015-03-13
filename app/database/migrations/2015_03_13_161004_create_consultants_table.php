<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consultants',function($table){
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->boolean('isEmailPublic')->default(0);
			$table->string('title')->nullable();
			$table->integer('hourlyMin')->unsigned()->default(100);

			$table->boolean('isAvailable')->default(1);
			$table->boolean('isRemote')->default(1);

			$table->string('country_id');
			$table->foreign('country_id')->references('id')->on('countries');
			$table->string('zip');		
			
			$table->boolean('isNotifiedOfRequests')->default(1);
			$table->boolean('isNotifiedOfRequestsEvenIfLowball')->default(0);
			$table->boolean('isNotifiedOfNewFeatures')->default(0);
			
			$table->string('password');
			$table->timestamps();
			$table->rememberToken();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('consultants');
	}

}
