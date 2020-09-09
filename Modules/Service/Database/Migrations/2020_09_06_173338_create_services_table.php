<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('price_type')->nullable()->default('normal');
            $table->string('gender')->nullable()->default('male');
            $table->double('price', 10, 2)->nullable();
            $table->double('min_price', 10, 2)->nullable();
            $table->double('max_price', 10, 2)->nullable();
            $table->boolean('for_children')->default(0)->nullable();
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
        Schema::dropIfExists('services');
    }
}
