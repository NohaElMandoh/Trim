<?php

namespace Modules\Governorate\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Governorate extends Model  implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name'];
    protected $fillable = [''];
    protected $dates = ['deleted_at'];

    public function cities() {
        return $this->hasMany('Modules\City\Entities\City');
    }
}
