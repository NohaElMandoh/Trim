<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGovernorateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governorate_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('governorate_id');
            $table->string('locale')->index();
            $table->string('name')->nullable();
        
            $table->unique(['governorate_id', 'locale']);
            $table->foreign('governorate_id')->references('id')->on('governorates')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('governorate_translations');
    }
}
