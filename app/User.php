<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'image', 'id_photo', 'commercial_register', 'is_active',
        'description', 'points', 'lat', 'lng',
        'city_id', 'governorate_id', 'sms_token', 'gender',
        'cover', 'is_sponsored'
    ];

    public static function days() {
        return [
            0 => __('Sunday'),
            1 => __('Monday'),
            2 =>  __('Tuesday'),
            3 =>  __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday')
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function offers()
    {
        return $this->hasMany('\Modules\Offer\Entities\Offer', 'shop_id');
    }

    public function products()
    {
        return $this->hasMany('\Modules\Product\Entities\Product', 'shop_id');
    }

    public function shop_orders()
    {
        return $this->hasMany('\Modules\Order\Entities\Order', 'shop_id');
    }

    public function images()
    {
        return $this->hasMany('\App\UserImage');
    }

    public function tokens()
    {
        return $this->hasMany('App\Token');
    }

    public static function findNearestCaptains($latitude, $longitude, $distance)
    {
        return User::select(DB::raw('*, ( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<=', $distance)
            ->where('is_active', 1)
            ->orderBy('distance')
            ->role('captain')
            ->with('tokens')
            ->get();
    }

    public function coupons()
    {
        return $this->belongsToMany('Modules\Coupon\Entities\Coupon')->withPivot('usage');
    }

    public function governorate()
    {
        return $this->belongsTo('Modules\Governorate\Entities\Governorate');
    }

    public function city()
    {
        return $this->belongsTo('Modules\City\Entities\City');
    }

    public function works()
    {
        return $this->hasMany('Modules\Salon\Entities\WorkDay', 'user_id');
    }

    public function services() {
        return $this->belongsToMany('Modules\Service\Entities\Service');
    }
}
