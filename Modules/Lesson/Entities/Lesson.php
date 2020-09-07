<?php

namespace Modules\Lesson\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;
    
    public $translatedAttributes = ['name'];
    protected $fillable = ['order', 'image', 'video', 'course_id'];
    protected $dates = ['deleted_at'];

    public function course() {
        return $this->belongsTo('\Modules\Course\Entities\Course');
    }
}