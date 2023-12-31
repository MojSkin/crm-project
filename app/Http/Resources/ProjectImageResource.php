<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
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
            'filename' => asset($this->file_name),
            'alt' => $this->alt,
            'type' => $this->type,
            'mime' => file_exists(public_path($this->file_name)) ? mime_content_type(public_path($this->file_name)) : 'unknown',
        ];
    }
}
