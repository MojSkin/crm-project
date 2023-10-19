<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactEditResource extends JsonResource
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
            'fName' => $this->fName,
            'lName' => $this->lName,
            'prefix' => $this->prefix,
            'nickname' => $this->nickName,
            'title' => $this->title,
            'organization' => $this->organization,
            'details' => ContactDetailResource::collection($this->details),
            'avatar' => $this->fileName != null && file_exists(public_path('storage/contact_avatars/' . $this->fileName)) ? asset('storage/contact_avatars/' . $this->fileName) : null
        ];
    }
}
