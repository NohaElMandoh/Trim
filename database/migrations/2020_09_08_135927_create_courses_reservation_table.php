<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('type');

            $table->string('name');
            $table->string('governorate_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_type')->nullable();

            $table->integer('course_id');
            $table->integer('user_id');
            $table->float('price')->nullable();

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
        Schema::dropIfExists('courses_reservations');
    }
}
