<?php

namespace Modules\Social\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Social extends Model
{
    protected $fillable = ['order', 'image', 'url'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
