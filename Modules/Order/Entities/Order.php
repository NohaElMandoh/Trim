<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $fillable = [
        'shop_id', 'branch_id', 'buy_lat', 'buy_lng',
        'delivery_lat', 'delivery_lng', 'user_id', 'captain_id',
        'status_id', 'shop_rate', 'shop_review', 'shop_review_image',
        'captain_rate', 'captain_review', 'captain_review_image', 'payment_method',
        'delivery_fee', 'payment_coupon', 'type', 'shop_name', 'phone'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function captain() {
        return $this->belongsTo('App\User', 'captain_id');
    }

    public function shop() {
        return $this->belongsTo('App\User', 'shop_id');
    }

    public function branch() {
        return $this->belongsTo('Modules\Branch\Entities\Branch', 'branch_id');
    }

    public function status() {
        return $this->belongsTo('Modules\Status\Entities\Status', 'status_id');
    }

    public function products() {
        return $this->belongsToMany('Modules\Product\Entities\Product')->withPivot('qty');
    }

    public function offers() {
        return $this->belongsToMany('Modules\Offer\Entities\Offer')->withPivot('qty');
    }

    public function messages() {
        return $this->hasMany('Modules\Order\Entities\Message');
    }

    public function items() {
        return $this->hasMany('Modules\Order\Entities\ItemOrder');
    }

    public function buy_locations() {
        return $this->hasMany('Modules\Order\Entities\BuyLocation');
    }

    public function delivery_locations() {
        return $this->hasMany('Modules\Order\Entities\DeliveryLocation');
    }
}
