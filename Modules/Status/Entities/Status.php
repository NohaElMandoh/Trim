<?php

namespace Modules\Status\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name'];
    protected $fillable = ['slug'];
    protected $dates = ['deleted_at'];
}