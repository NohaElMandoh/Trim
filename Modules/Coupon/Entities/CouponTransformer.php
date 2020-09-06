<?php

namespace Modules\Coupon\Entities;

use League\Fractal;
use Modules\Coupon\Entities\Coupon;

class CouponTransformer extends Fractal\TransformerAbstract
{
    public function transform(Coupon $coupon)
    {
        return [
            'id'            => (int) $coupon->id,
            'code'          => $coupon->code,
            'duration'      => $coupon->duration,
            'title'         => $coupon->title,
            'usage_number_times' => $coupon->usage_number_times,
            'image'         => route('file_show', $coupon->image),
            'anywhere'      => $coupon->anywhere,
            'moreway'       => $coupon->moreway,
            'oneway'        => $coupon->oneway,
            'oq'            => $coupon->oq,
            'week'          => $coupon->week,
            'price'         => $coupon->price,
            'created_at'    => date('Y-m-d h:i A', strtotime($coupon->created_at)),
        ];
    }
}
