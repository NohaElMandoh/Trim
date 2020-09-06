<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name'];
    protected $fillable = ['order', 'price', 'shop_id', 'image', 'category_id'];
    protected $dates = ['deleted_at'];

    public function shop() {
        return $this->belongsTo('App\User', 'shop_id');
    }

    public function category() {
        return $this->belongsTo('Modules\Category\Entities\Category', 'category_id');
    }
}