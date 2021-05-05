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

        return [
         'data'=>[  'id' => $this->id,
         'name' => $this->name,
         'email' => $this->email,
         'phone' => $this->phone,
         'image' => route('file_show', $this->image),
         'governorate_en' => (!empty($this->governorate->name)) ? $this->governorate->name : "",
         'governorate_ar' => (!empty($this->governorate->name)) ? $this->governorate->translate('ar')->name : "",
         'city_en' => (!empty($this->city->name)) ? $this->city->name : "",
         'city_ar' => (!empty($this->city->name)) ? $this->city->translate('ar')->name : "",
         'gender' => $this->gender,
         'rate' => $this->rate,
         'commentsCount' => $this->rateSalon()->count(),
         'lat' => number_format($this->lat, 0, '', ''),
         'lang' => number_format($this->lng, 0, '', ''),
         'address' => (!empty($this->address)) ? $this->address : "",
         'status' => $this->status,
         'from' => $this->from,
         'to' => $this->to,
         'services' => ServiceResource::collection($this->services),
         'rates' => RateResource::collection($this->rateSalon),
         'is_fav' => $this->is_fav,
         'type' => $this->type,
         'offers' => OfferResource::collection($this->offers),],
        
              
                
        ];
    }
}
