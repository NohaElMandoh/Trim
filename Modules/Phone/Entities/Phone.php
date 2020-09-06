<?php

namespace Modules\Phone\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    protected $fillable = ['phone', 'order'];
    protected $dates = ['deleted_at'];

    use SoftDeletes;
}
