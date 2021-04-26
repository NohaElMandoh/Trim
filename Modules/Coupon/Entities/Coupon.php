<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;

    public $translatedAttributes = ['title'];
    protected $fillable = [
        'code', 'name', 'duration', 'usage_number_times', 'image',
        'anywhere', 'moreway', 'oneway', 'oq', 'week', 'price',
        'city_id', 'governorate_id'
    ];
    protected $dates = ['deleted_at'];
 
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('usage');
    }

    public function roles()
    {
        return $this->belongsToMany(config('permission.models.role'));
    }

    public function governorate()
    {
        return $this->belongsTo('Modules\Governorate\Entities\Governorate');
    }

    public function city()
    {
        return $this->belongsTo('Modules\City\Entities\City');
    }
}
