<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['price_type', 'gender', 'price', 'min_price', 'max_price', 'for_children'];
    protected $dates = ['deleted_at'];

    public function users() {
        return $this->belongsToMany('App\User','service_user');
    }

    public function cart()
    {
        return $this->morphMany('App\Cart', 'item');
    }
}