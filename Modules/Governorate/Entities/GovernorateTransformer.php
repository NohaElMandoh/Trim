<?php

namespace Modules\Governorate\Entities;

use League\Fractal;
use League\Fractal\Manager;
use Modules\City\Entities\CityTransformer;
use Modules\Governorate\Entities\Governorate;

class GovernorateTransformer extends Fractal\TransformerAbstract
{
    public $fractal;
    protected $availableIncludes = [
        'cities'
    ];


    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform(Governorate $governorate)
    {
        return [
            'id'        => (int) $governorate->id,
            'name'      => $governorate->name,
        ];
    }

     /**
     * Include Cities
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCities($governorate)
    {
        $cities = $governorate->cities()->latest()->get();
        return $this->collection($cities, new CityTransformer);
    }
}
