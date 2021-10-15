<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkDaysResource extends JsonResource
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
            'day'=>$this->day,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'day_name' => $this-> day_name,
        ];
    }

  
    
}
