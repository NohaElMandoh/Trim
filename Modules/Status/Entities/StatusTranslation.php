<?php

namespace Modules\Status\Entities;

use Illuminate\Database\Eloquent\Model;

class StatusTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
