<?php

namespace Modules\City\Entities;

use League\Fractal;
use League\Fractal\Manager;
use Modules\City\Entities\City;

class CityTransformer extends Fractal\TransformerAbstract
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform(City $city)
    {
        return [
            'id'        => (int) $city->id,
            'name'      => $city->name,
        ];
    }
}
