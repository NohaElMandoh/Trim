<?php

namespace Modules\Feature\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['title'];
    protected $fillable = ['order', 'image'];
    protected $dates = ['deleted_at'];
}