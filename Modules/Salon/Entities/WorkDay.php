<?php

namespace Modules\Salon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkDay extends Model
{
    protected $fillable = ['from', 'to', 'day', 'user_id'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
