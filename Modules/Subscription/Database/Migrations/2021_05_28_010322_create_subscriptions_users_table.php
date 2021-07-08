<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions_users', function (Blueprint $table) {
        	$table->increments('id');
           
            $table->integer('user_id');
            $table->integer('subsccription_id');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->float('price')->nullable();
            $table->integer('months');
            $table->integer('is_active')->default(0);
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
        Schema::dropIfExists('subscriptions_users');
    }
}
