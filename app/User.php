<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use App\Models\LinkedSocialAccount;
use Carbon\Carbon;

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
        'cover', 'is_sponsored', 'provider', 'provider_id', 'provider_token', 'type','search'
    ];
    protected $appends = ['rate', 'status', 'from', 'to', 'avaliable_dates','is_fav'];

    public static function days()
    {
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
    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }
    public function offers()
    {
        return $this->hasMany('\Modules\Offer\Entities\Offer', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('\Modules\Product\Entities\Product', 'shop_id');
    }
    public function orders()
    {
        return $this->hasMany('\Modules\Order\Entities\Order');
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
    public function rates()
    {
        return $this->belongsTo('App\Rate', 'id', 'user_id');
    }
    public function favorities()
    {
        return $this->belongsTo('App\Favorite', 'id', 'user_id');
    }
    public function favorities2()
    {
        return $this->belongsToMany('App\User', 'favorites','salon_id','user_id')->withpivot('is_fav')->withTimestamps();
    }
    // public function favorities2()
    // {
    //     return $this->belongsToMany('App\User', 'favorites','user_id','salon_id')->withpivot('is_fav')->withTimestamps();
    // }
    public function rateSalon()
    {
        return $this->hasMany('App\Rate', 'salon_id');
    }
    

    public function getIsFavAttribute($value)
    {
        foreach($this->favorities2 as $fav)
        {
            if(($fav->pivot->user_id ==Auth()->user()->id) && ($fav->pivot->is_fav =='1') )
            return true;
        }
        return false;
       
    }
    public function getRateAttribute($value)
    {
        $sumRating = $this->rateSalon()->sum('rate');
        $countRating = $this->rateSalon()->count();
        $avgRating = 0;
        if ($countRating > 0) {
            $avgRating = round($sumRating / $countRating, 1);
        }
        return $avgRating;
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

    public function services()
    {
        return $this->belongsToMany('Modules\Service\Entities\Service', 'service_user');
    }
    // public function cart ()
    // {
    //     return $this->hasMany('App\Cart');
    // }
    public function cart ()
    {
        return $this->belongsToMany('Modules\Product\Entities\Product', 'carts')->withPivot('price', 'quantity',  'id')->withTimestamps();
    }
    public function getStatusAttribute($value)
    {
        $d    = Carbon::now();
        $currentDay = $d->format('l');
        $now = Carbon::now()->toDateTimeString();

        $days = $this->works()->pluck('day');
        $daysNames = collect($this->days());
        $daysAvaliable = $daysNames->only($days);
        foreach ($daysAvaliable as $i => $day) {
            if ($day == $currentDay) {
                $workday = $this->works()->where('day', $i)->first();
                if ($workday->from_date >= $now && $workday->to_date <= $now) {
                    return __('messages.open');
                } else  return __('messages.closed');
            }
        }
        return __('messages.closed');
    }
    public function getFromAttribute($value)
    {
        $d    = Carbon::now();
        $currentDay = $d->format('l');
        $now = Carbon::now()->toDateTimeString();
        $from = "";
        $days = $this->works()->pluck('day');
        $daysNames = collect($this->days());
        $daysAvaliable = $daysNames->only($days);
        foreach ($daysAvaliable as $i => $day) {
            if ($day == $currentDay) {
                $workday = $this->works()->where('day', $i)->first();
                if ($workday->from_date >= $now && $workday->to_date <= $now) {
                    $from = $workday->from_date;
                }
            }
        }
        return $from;
    }
    public function getToAttribute($value)
    {
        $d    = Carbon::now();
        $currentDay = $d->format('l');
        $now = Carbon::now()->toDateTimeString();
        $to = "";
        $days = $this->works()->pluck('day');
        $daysNames = collect($this->days());
        $daysAvaliable = $daysNames->only($days);
        foreach ($daysAvaliable as $i => $day) {
            if ($day == $currentDay) {
                $workday = $this->works()->where('day', $i)->first();
                if ($workday->from_date >= $now && $workday->to_date <= $now) {
                    $to = $workday->to_date;
                }
            }
        }
        return $to;
    }
    public function getAvaliableDatesAttribute($value)
    {
        $d    = Carbon::now();
        $currentDay = $d->format('l');
        $now = Carbon::now()->toDateTimeString();
        $to = "";
        $from = null;
        $dates = [];
        $days = $this->works()->pluck('day');
        $daysNames = collect($this->days());
        $daysAvaliable = $daysNames->only($days);
        foreach ($daysAvaliable as $i => $day) {
            if ($day == $currentDay) {
                $workday = $this->works()->where('day', $i)->first();

                if ($workday->from <= $now && $workday->to <= $now) {
                    $to = $workday->to;
                    $from = $workday->from;
                    $from_date = Carbon::createFromFormat('H:i:s', $from);
                    $to_date = Carbon::createFromFormat('H:i:s', $to);
                    $diff = $to_date->diff($from_date);

                    for ($i = $from_date; $i <= $to_date; $i->modify('+30 minute')) {
                        array_push($dates, $i->format('g:i A'));
                    }
                }
            }
        }
        //  $from_add=(new Carbon($from))->addMinutes(30);

        // $text = $from_add->format('g:i A');
        // $replace = str_replace('AM', 'ص', $text);
        // $replace = str_replace('PM', 'م', $replace);
        return $dates;
    }
    public function routeNotificationForNexmo(){
        return $this->phone;
    }
}
