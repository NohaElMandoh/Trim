<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model  implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['title','desc'];
    protected $fillable = ['price','months','currency','origion_price'];
    protected $dates = ['deleted_at'];

    public function cities() {
        return $this->hasMany('Modules\City\Entities\City');
    }
    public function user()
    {
        return $this->belongsToMany('App\User','subscriptions_users');
    }
}
