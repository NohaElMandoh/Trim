<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentResource extends JsonResource
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
            'user' => $this->user->name,
            // 'image'=> route('file_show',  $this->user->image),
            'image' => !empty($this->user->image) ? url($this->user->image) : url('uploads/user.png'),
            'comment' => $this->comment,
            'created_at'=>(!empty($this->created_at))?$this->created_at->format('g:i A'):""
            
        ];
    }
}
