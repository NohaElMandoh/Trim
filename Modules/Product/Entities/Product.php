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
    protected $fillable = ['order', 'price', 'image', 'category_id','shop_id','user_id'];
    protected $dates = ['deleted_at'];

    public function category() {
        return $this->belongsTo('Modules\Category\Entities\Category', 'category_id');
    }

}
