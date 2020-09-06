<?php

namespace Modules\Feature\Entities;

use Illuminate\Database\Eloquent\Model;

class FeatureTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title'];
}
