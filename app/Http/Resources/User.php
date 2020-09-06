<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'image' => route('file_show', $this->image),
            'points' => $this->points,
            'birth_date' => $this->birth_date,
            'job' => $this->job,
            'governorate_id' => (int) $this->governorate_id,
            'city_id' => (int) $this->city_id,
        ];
    }
}
