<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [

            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => route('file_show', $this->image),
            // 'points' => $this->points,   (isset($i['note'])) ? $i['note'] : ''
            'governorate' => (!empty($this->governorate->name)) ? $this->governorate->name : "",
            'city' => (!empty($this->city->name)) ? $this->city->name : "",
            'gender' => $this->gender,
            'rate' => $this->rate,
            'commentsCount' => $this->rateSalon()->count(),
            'lat' => $this->lat,
            'lang' => $this->lng,
            'address' => $this->address,
            'status' => $this->status,
            'from' => $this->from,
            'to' => $this->to,
            // 'avaliable_dates'=>$this->avaliable_dates,
            'services' => ServiceResource::collection($this->services),
            'rates' => RateResource::collection($this->rateSalon),
            // 'rates' =>$this->rateSalon

            // 'works'=>$this->works,
        ];
    }
}
