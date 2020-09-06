<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    protected $fillable = ['message', 'order_id', 'type', 'user_id', 'complaint_id'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function order() {
        return $this->belongsTo('Modules\Order\Entities\Order');
    }
    public function user() {
        return $this->belongsTo('App\User');
    }
}
