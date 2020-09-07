<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name', 'description'];
    protected $fillable = ['order', 'price', 'image'];
    protected $dates = ['deleted_at'];
}