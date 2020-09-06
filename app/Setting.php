<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Setting extends Model implements TranslatableContract
{
    use Translatable;
    
    public $translatedAttributes = [
        'title', 'description', 'copyrights', 'privacy',
        'how_it_works', 'work_in_oq'
    ];
    protected $fillable = [
        'logo', 'icon', 'point_price',
        'header_logo', 'google_play_logo', 'app_store_logo',
        'header_screenshot', 'app_features_image', 'delivery_image',
        'google_play_user_app', 'google_play_captain_app', 'app_store_user_app',
        'app_store_captain_app',
    ];
}