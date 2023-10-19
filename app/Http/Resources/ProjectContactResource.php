<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->contact->id,
            'name' => ($this->contact->lName ?? '') . ' ' . ($this->contact->fName ?? ''),
            'nickName' => $this->contact->nickName,
            'position' => $this->position,
        ];
    }
}
