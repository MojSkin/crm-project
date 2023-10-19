<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectNoteResource extends JsonResource
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
            'user' => new ProjectUserResource($this->user),
            'note' => $this->note,
            'project_status' => $this->project_status,
            'project_result' => $this->project_result,
            'created_at' => $this->created_at,
            'alarms' => $this->alarms,
        ];
    }
}
