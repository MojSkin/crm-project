<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlarmResource extends JsonResource
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
            'alarm_date' => $this->alarm_date,
            'alarm_time' => $this->alarm_time,
            'weekdays' => $this->weekdays != null ? array_map('intval', explode('|', substr($this->weekdays, 1, strlen($this->weekdays)-2))) : [],
            'is_active' => $this->is_active,
            'user' => $this->user(),
        ];
    }
}
