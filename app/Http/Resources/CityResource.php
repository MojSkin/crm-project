<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'county' => [
                'id' => $this->county_id,
                'title' => $this->county->name ?? 'نامشخص'
            ],
            'province' => [
                'id' => $this->province_id,
                'title' => $this->province->name ?? 'نامشخص'
            ],
        ];
    }
}
