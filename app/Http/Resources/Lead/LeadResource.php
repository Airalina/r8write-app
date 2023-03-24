<?php

namespace App\Http\Resources\Lead;

use App\Http\Resources\Seller\SellerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $lead = $this->leads->first();

        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "dni" => $this->dni,
            "email" => $this->email,
            "address" => $lead->address,
            "phone" =>  $lead->phone,
            "site_url" =>  $lead->site_url,
            "description" =>  $lead->description,
        ];
    }
}
