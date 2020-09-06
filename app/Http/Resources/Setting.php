<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Setting extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'point_price' =>  $this->point_price,
            'privacy' =>  $this->privacy,
            'how_it_works' => $this->how_it_works,
            'work_in_oq' => $this->work_in_oq,
        ];
    }
}
