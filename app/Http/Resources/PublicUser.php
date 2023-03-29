<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicUser extends JsonResource
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
           'id'               => $this->id,
           'name'             => $this->name,
           'slug'             => $this->slug,
           'image_url'        => $this->image_url,
           'logo_url'         => $this->logo_url,
           'licence_state'    => $this->licence_state,
           'license_number'   => $this->license_number,
       ];
    }
}
