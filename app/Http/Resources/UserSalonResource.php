<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSalonResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            // 'image' => route('file_show', $this->image),
            // 'cover' => route('file_show', $this->cover),
            // 'image' => !empty($this->image) ? url($this->image) : url('uploads/user.png'),
            'image' => strpos($this->image, 'http') === 0 ?$this->image: url($this->image),

            'cover' => !empty($this->cover) ? url($this->cover) : url('uploads/user.png'),
            'governorate_en' => (!empty($this->governorate->name)) ? $this->governorate->name : "",
            'governorate_ar' => (!empty($this->governorate->name)) ? $this->governorate->translate('ar')->name : "",
            'city_en' => (!empty($this->city->name)) ? $this->city->name : "",
            'city_ar' => (!empty($this->city->name)) ? $this->city->translate('ar')->name : "",
      
            'birth_date' => $this->birth_date,
      
            'gender'=>$this->gender,
            'lat' =>(!empty($this->lat))?  (float) $this->lat:  (float) 0 ,
            'lang' => (!empty($this->lng))?   (float) $this->lng:  (float) 0 ,
            'type' => $this->type,
        ];
    }
}
