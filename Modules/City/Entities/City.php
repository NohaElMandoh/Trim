<?php

namespace Modules\City\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model  implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name'];
    protected $fillable = ['governorate_id'];
    protected $dates = ['deleted_at'];

    public function governorate() {
        return $this->belongsTo('Modules\Governorate\Entities\Governorate');
    }
}
