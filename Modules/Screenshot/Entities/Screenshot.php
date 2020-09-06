<?php

namespace Modules\Screenshot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screenshot extends Model
{
    protected $fillable = ['title', 'image', 'order'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
