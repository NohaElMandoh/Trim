<?php

namespace Modules\Lesson\Entities;

use Illuminate\Database\Eloquent\Model;

class LessonTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
