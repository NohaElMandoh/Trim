<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImage extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'image', 'order'];

    protected $dates = ['deleted_at'];
}
