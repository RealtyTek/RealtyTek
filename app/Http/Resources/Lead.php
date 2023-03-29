<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Lead extends JsonResource
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
            'id'             => $this->id,
            'parent_id'      => $this->parent_id,
            'image_url'      => $this->image_url,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'mobile_no'      => $this->mobile_no,
            'email'          => $this->email,
            'address'        => $this->address,
            'city'           => $this->city,
            'state'          => $this->state,
            'zipcode'        => $this->zipcode,
            'is_agrement_accept'=> $this->is_agrement_accept,
			'invitation_acceptance' => $this->invitation_acceptance == 0 ? 'Pending' : 'Accepted',
			'created_at'     => $this->created_at
            
        ];
    }
}
