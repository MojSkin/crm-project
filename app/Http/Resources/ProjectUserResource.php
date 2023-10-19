<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $details = [];
        foreach($this->details as $d) {
            $details[$d->title] = $d->value;
        }
        if (!isset($details['avatar'])) {
            $details['avatar'] = asset('/assets/img/avatars/placeholder.jpg');
        } else {
            $details['avatar'] = asset('/storage/uploads/user_avatars/'.$details['avatar']);
        }
        return [
            'id' => $this->id,
            'username' => $this->username,
            'fName' => $details['fName'] ?? null,
            'lName' => $details['lName'] ?? null,
            'nickname' => $details['nickname'] ?? null,
            'display_name' => strlen(($details['fName'] ?? '').($details['lName'] ?? '')) > 0 ? trim(($details['fName'] ?? '').' '.($details['lName'] ?? '')) : $details['nickname'] ?? $this->username,
            'avatar' => $details['avatar'],
            'details' => $details
        ];
    }
}
