<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
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
            'due_date' => Carbon::createFromDate($this->due_date)->format('Y/m/d H:i'),
            'flag' => $this->flags[$this->flag] ?? 'عادی',
            'is_done' => $this->is_done,
            'user' => $this->user,
            'created_at' => Carbon::createFromDate($this->created_at)->format('Y/m/d H:i'),
            'updated_at' => Carbon::createFromDate($this->updated_at)->format('Y/m/d H:i'),
        ];
    }
}
