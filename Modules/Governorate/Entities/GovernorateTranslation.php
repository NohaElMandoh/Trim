<?php

namespace Modules\Governorate\Entities;

use Illuminate\Database\Eloquent\Model;

class GovernorateTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
