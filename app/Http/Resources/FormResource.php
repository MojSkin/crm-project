<?php

namespace App\Http\Resources;

use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            'active' => $this->active,
            'fields' => FormDetailResource::collection(FormField::whereFormId($this->id)->orderBy('tab_index')->get())
        ];
    }
}
