<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('image')->default('user.png')->nullable();
            $table->string('cover')->default('cover.png')->nullable();
            $table->string('id_photo')->nullable();
            $table->string('commercial_register')->nullable();
            $table->text('description')->nullable();
            $table->string('password');
            $table->boolean('is_active')->nullable()->default(0);
            $table->string('gender')->nullable()->default('male');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
