<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $objs = Contact::getDefault('additional_infos')[$this->section]['options'];
        $keys = array_column($objs, 'label');
        $index = array_search($this->title, $keys);
        return [
            'section' => $this->section,
            'title' => $index !== false ? Contact::getDefault('additional_infos')[$this->section]['options'][$index]['title'] : $this->title,
            'label' => $this->title,
            'value' => $this->value,
            'is_other' => $index === false,
            'validations' => Contact::getDefault('additional_infos')[$this->section]['options'][$index ?? 0]['validations'],
        ];
    }
}
