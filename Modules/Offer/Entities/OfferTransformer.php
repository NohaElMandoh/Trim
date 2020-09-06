<?php

namespace Modules\Offer\Entities;

use League\Fractal;
use Modules\Offer\Entities\Offer;

class OfferTransformer extends Fractal\TransformerAbstract
{
    public function transform(Offer $offer)
    {
        return [
            'id'            => (int) $offer->id,
            'name'          => $offer->name,
            'category_id'   => $offer->category_id ?? '',
            'description'   => $offer->description,
            'order'         => $offer->order,
            'price'         => $offer->price,
            'image'         => route('file_show', $offer->image),
            'shop'          => [
                'id'            => $offer->shop->id ?? '',
                'name'          => $offer->shop->name ?? '',
                'category_id'   => $offer->shop->category_id ?? '',
                'image'         => route('file_show', $offer->shop->image ?? ''),
            ],
        ];
    }
}
