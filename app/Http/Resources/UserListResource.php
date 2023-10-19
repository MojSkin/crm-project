<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $det = [];
        foreach ($this->details as $d) {
            if (gettype($d) === 'object') {
                if ($d->title === 'avatar') {
                    if (file_exists(storage_path('app/public/uploads/user_avatars/'.$d->value))) {
                        $det[$d->title] = asset('/storage/uploads/user_avatars/'.$d->value);
                    } else {
                        $det[$d->title] = asset('/assets/img/avatars/placeholder.jpg');
                    }
                } else if ($d->title === 'socials') {
                    $det[$d->title] = json_decode($d->value);
                } else {
                    $det[$d->title] = $d->value;
                }
            }
        }
        if (!isset($det['avatar'])) {
            $det['avatar'] = asset('/assets/img/avatars/placeholder.jpg');
        }
        $count = count($det);
        $det = (object) $det;
        $username = $this->username ?? $this->email;
        $fName    = $count > 0 ? $det->fName ?? '' : '';
        $lName    = $count > 0 ? $det->lName ?? '' : '';
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'email_verified_at' => $this->email_verified_at,
            'mobile_verified_at' => $this->mobile_verified_at,
            'user_role_id' => $this->user_role_id,
            'role' => $this->role,
            'details' => $det,
            'display_name' => strlen($fName.$lName) > 0 ? $fName.' '.$lName : $username,
        ];
    }
}
