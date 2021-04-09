<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('cost')->nullable();
            $table->float('discount')->nullable();
            $table->float('total')->nullable();
            $table->string('reservation_time')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('cost');
            $table->dropColumn('discount');
            $table->dropColumn('total');
            $table->dropColumn('reservation_time');

        });
    }
}
