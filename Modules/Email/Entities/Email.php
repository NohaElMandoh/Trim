<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    protected $fillable = ['email', 'order'];
    protected $dates = ['deleted_at'];
    use SoftDeletes;
}
