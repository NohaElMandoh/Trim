<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('usage_number_times')->nullable();
            $table->string('image')->nullable();
            $table->boolean('anywhere')->default(0)->nullable();
            $table->boolean('moreway')->default(0)->nullable();
            $table->boolean('oneway')->default(0)->nullable();
            $table->boolean('oq')->default(0)->nullable();
            $table->boolean('week')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
