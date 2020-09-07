<?php

namespace Modules\Product\Entities;

use League\Fractal;
use Modules\Product\Entities\Product;

class ProductTransformer extends Fractal\TransformerAbstract
{
    public function transform(Product $offer)
    {
        return [
            'id'            => (int) $offer->id,
            'name'          => $offer->name,
            'category_id'   => $offer->category_id ?? '',
            'order'         => $offer->order,
            'price'         => $offer->price,
            'image'         => route('file_show', $offer->image),
        ];
    }
}
