<?php

namespace Modules\Package\Entities;

use League\Fractal;
use League\Fractal\Manager;
use Modules\Package\Entities\Package;

class PackageTransformer extends Fractal\TransformerAbstract
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform(Package $package)
    {
        return [
            'id'            => (int) $package->id,
            'description'   => $package->description,
            'order'         => $package->order,
            'price'         => $package->price,
            'points'        => $package->points,
        ];
    }
}
