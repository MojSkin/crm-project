<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $ext = pathinfo(base_path($this->file_name),  PATHINFO_EXTENSION) ?? 'unknown';
        return [
            'id' => $this->id,
            'filename' => asset($this->file_name),
            'alt' => $this->alt,
            'ext' => strlen($ext)>0 ? $ext : 'unknown',
            'size' => file_exists(public_path($this->file_name)) ? filesize(public_path($this->file_name)) : 0,
            'user' => new ProjectUserResource($this->u),
            'mime' => file_exists(public_path($this->file_name)) ? mime_content_type(public_path($this->file_name)) : 'unknown',
            'created_at' => $this->created_at,
        ];
    }
}
