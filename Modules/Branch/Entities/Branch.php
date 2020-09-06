<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['address'];
    protected $fillable = ['lat', 'lng', 'user_id'];
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}