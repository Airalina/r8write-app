<?php

namespace App\Http\Resources\Quote;

use App\Http\Resources\Seller\SellerResource;
use App\Http\Resources\Service\ServiceResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = new Carbon($this->date ?? null);
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "user" => new SellerResource($this->owner),
            "date" => $date->format('Y-m-d'),
            "description" => $this->description,
            "services" => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
