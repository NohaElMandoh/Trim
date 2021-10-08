<?php

namespace Modules\Offer\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;

    public $translatedAttributes = ['name', 'description'];
    protected $fillable = ['price', 'user_id', 'image', 'is_sponsored', 'category_id', 'service_id', 'service_price'];
    protected $dates = ['deleted_at'];
    protected $appends = ['discount'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('Modules\Category\Entities\Category');
    }
    public function cart()
    {
        return $this->morphMany('App\Cart', 'item');
    }
    public function service()
    {
        return $this->belongsTo('Modules\Service\Entities\Service');
    }
    public function getDiscountAttribute($value)
    {
        if ($this->service_price > 0 && $this->service_price  != null)
            $dis = round(($this->price / $this->service_price) * 100, 0);
        else $dis = 0;
        return  $dis;
    }
}
