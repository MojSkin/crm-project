<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'user_percentage' => $this->user_percentage,
            'project_type' => $this->project_type,
            'contacts' => ProjectContactResource::collection($this->contacts),
            'covers' => count($this->covers) > 0 ? ProjectImageResource::collection($this->covers) : [['id' => -1, 'filename' => asset('assets/images/png/no-image.png')]],
            'images' => ProjectImageResource::collection($this->images),
            'files' => ProjectFileResource::collection($this->files),
            'comments' => ProjectCommentResource::collection($this->comments),
            'notes' => ProjectNoteResource::collection($this->notes),
            'last_note' => new ProjectNoteResource($this->notes->last()),
            'members' => ProjectUserResource::collection($this->members),
            'user' => new ProjectUserResource($this->user),
            'city' => new CityResource($this->city),
            'region' => $this->region,
            'main_street' => $this->main_street,
            'aux1' => $this->aux1,
            'aux2' => $this->aux2,
            'alley1' => $this->alley1,
            'alley2' => $this->alley2,
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'po_code' => $this->po_code,
            'blocks' => $this->blocks,
            'units' => $this->units,
            'floors' => $this->floors,
            'created_at' => $this->created_at,
        ];
    }
}
