<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyLocation extends Model
{
    protected $fillable = ['order_id', 'name', 'lat', 'lng'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function order() {
        return $this->belongsTo('Modules\Order\Entities\Order');
    }

    public function items () {
        return $this->hasMany('Modules\Order\Entities\ItemOrder', 'buy_id');
    }
}
