<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StarsResource extends JsonResource
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
            'image' => route('file_show', $this->image),
            'cover' => route('file_show', $this->cover),
            'rate' => $this->rate,
            
        ];
    }
}
