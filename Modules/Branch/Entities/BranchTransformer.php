<?php

namespace Modules\Branch\Entities;

use League\Fractal;
use Modules\Branch\Entities\Branch;

class BranchTransformer extends Fractal\TransformerAbstract
{
    public function transform(Branch $branch)
    {
        return [
            'id'            => (int) $branch->id,
            'address'       => $branch->address,
            'lat'           => $branch->lat,
            'lng'           => $branch->lng,
            'user_id'       => $branch->user_id,
            'distance'      => $branch->distance ?? '',
        ];
    }
}
