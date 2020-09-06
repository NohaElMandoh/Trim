<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemOrder extends Model
{
    protected $fillable = ['name', 'order_id', 'qty', 'price', 'buy_id', 'delivery_id'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function order() {
        return $this->belongsTo('Modules\Order\Entities\Order');
    }

    public function buy_location () {
        return $this->belongsTo('Modules\Order\Entities\BuyLocation', 'buy_id');
    }

    public function delivery_location () {
        return $this->belongsTo('Modules\Order\Entities\DeliveryLocation', 'delivery_id');
    }
}
