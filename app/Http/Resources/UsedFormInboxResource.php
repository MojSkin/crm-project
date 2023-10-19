<?php

namespace App\Http\Resources;

use App\Models\UsedFormDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsedFormInboxResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => $this->user,
            'transaction' => $this->transaction,
            'status' => $this->status,
            'date' => $this->date,
            'user_ip' => $this->user_ip,
            'invoice' => $this->invoice,
            'rejects' => $this->rejects,
            'fields' => UsedFormInboxDetailsResource::collection($this->details)
        ];
    }
}
