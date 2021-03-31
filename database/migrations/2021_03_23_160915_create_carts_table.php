<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function(Blueprint $table) {
        
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            // $table->integer('item_id'); 
            $table->morphs('product'); //notifiable  App\item | App\offer

            $table->integer('user_id');
            $table->decimal('price');
            $table->integer('quantity');
            $table->text('note')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('carts');
    }
}
