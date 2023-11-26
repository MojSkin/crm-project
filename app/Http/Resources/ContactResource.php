<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'prefix' => $this->prefix != 0 ? Contact::getDefault('prefix_text')[$this->prefix]['text']:'',
            'name' => ($this->lName ?? '') . ' ' . ($this->fName ?? ''),
            'nickName' => $this->nickName,
            'title' => $this->title,
            'organization' => $this->organization,
            'tags' => $this->tags ?? [],
            'avatar' => $this->fileName != null && file_exists(public_path('storage/contact_avatars/' . $this->fileName)) ? asset('storage/contact_avatars/' . $this->fileName) : null,
            'details' => ContactDetailResource::collection($this->details)
        ];
    }
}
