<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->foreign('account_id')
					->references('id')->on('accounts')
					->onDelete('cascade');
			$table->string('added_by');
			$table->date('date');
			$table->string('tag');
			$table->string('description');
			$table->float('amount');
			$table->boolean('withdraw');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions');
	}

}
