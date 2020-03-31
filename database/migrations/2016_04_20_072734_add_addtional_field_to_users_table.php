<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddtionalFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->string('username');
//            $table->bigInteger('cust_id');
//            $table->string('first_name');
//            $table->string('last_name');
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('username');
//            $table->dropColumn('cust_id');
//            $table->dropColumn('first_name');
//            $table->dropColumn('last_name');
        });
    }
}
