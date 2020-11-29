<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactsAPIResource extends JsonResource
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
            'mapped_attributes' => [
                'team_id' => $this->team_id,
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'sticky_phone_number_id' => $this->sticky_phone_number_id,
            ],
            'custom_attributes' => CustomAttributesResource::collection($this->customAttributes)
        ];
    }
}
