<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeaderLogoToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('header_logo')->nullable()->default('logo.png');
            $table->string('google_play_logo')->nullable()->default('google_play.png');
            $table->string('app_store_logo')->nullable()->default('app_store.png');
            $table->string('header_screenshot')->nullable()->default('header_screenshot.png');
            $table->string('app_features_image')->nullable()->default('app_features_image.png');
            $table->string('delivery_image')->nullable()->default('delivery_image.png');
            $table->string('google_play_user_app')->nullable();
            $table->string('google_play_captain_app')->nullable();
            $table->string('app_store_user_app')->nullable();
            $table->string('app_store_captain_app')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'header_logo',
                'google_play_logo',
                'app_store_logo',
                'header_screenshot',
                'app_features_image',
                'delivery_image',
                'google_play_user_app',
                'google_play_captain_app',
                'app_store_user_app',
                'app_store_captain_app',
            ]);
        });
    }
}
