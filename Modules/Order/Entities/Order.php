<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $fillable = [
        'lat', 'lng', 'user_id', 'barber_id',
        'status_id', 'rate', 'review', 'review_image',
        'payment_method', 'payment_coupon', 'phone','payment_id',
        'address', 'is_now', 'type', 'work_day_id','cost','discount','total','reservation_time','reservation_day','approve','cancel_reason','order_type'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function barber() {
        return $this->belongsTo('App\User', 'barber_id');
    }

    public function status() {
        return $this->belongsTo('Modules\Status\Entities\Status', 'status_id');
    }

    public function work_day() {
        return $this->belongsTo('Modules\Salon\Entities\WorkDay', 'work_day_id');
    }

    public function products() {
        return $this->belongsToMany('Modules\Product\Entities\Product')->withPivot('qty','price','total');
    }

    public function services() {
        return $this->belongsToMany('Modules\Service\Entities\Service')->withPivot('qty','price','total');
    }

    public function offers() {
        return $this->belongsToMany('Modules\Offer\Entities\Offer')->withPivot('qty','price','total');
    }

    public function messages() {
        return $this->hasMany('Modules\Order\Entities\Message');
    }

   
}
