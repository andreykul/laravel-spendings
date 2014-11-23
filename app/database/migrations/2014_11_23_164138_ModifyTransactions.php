<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTransactions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions', function($table)
		{
		    $table->dropColumn('added_by');
		    $table->float('balance');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions', function($table)
		{
		    $table->string('added_by');
		    $table->dropColumn('balance');
		});
	}

}
