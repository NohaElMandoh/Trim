<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('buy_lat')->nullable();
            $table->double('buy_lng')->nullable();
            $table->double('delivery_lat')->nullable();
            $table->double('delivery_lng')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('captain_id')->nullable();
            $table->foreign('captain_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('shop_rate')->nullable();
            $table->string('shop_review')->nullable();
            $table->string('shop_review_image')->nullable();
            $table->tinyInteger('captain_rate')->nullable();
            $table->string('captain_review')->nullable();
            $table->string('captain_review_image')->nullable();
            $table->string('payment_method')->nullable();
            $table->double('delivery_fee', 10, 2)->nullable();
            $table->string('payment_coupon')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->default('oq')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
