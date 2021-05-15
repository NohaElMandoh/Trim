<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
}
