<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormDetailResource extends JsonResource
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
            'name' => $this->name,
            'title' => $this->title,
            'hint' => $this->hint,
            'description' => $this->description,
            'placeholder' => $this->placeholder,
            'type' => $this->type,
            'default' => $this->default,
            'is_required' => $this->is_required,
            'min' => $this->min,
            'max' => $this->max,
            'max_rows' => $this->max_rows,
            'steps' => $this->steps,
            'options' => $this->options,
            'tab_index' => $this->tab_index,
            'col_mobile' => 'is-'.$this->col_mobile.'-mobile',
            'col_tablet' => 'is-'.$this->col_tablet.'-tablet',
            'col_desktop' => 'is-'.$this->col_desktop.'-desktop',
            'col_widescreen' => 'is-'.$this->col_widescreen.'-widescreen',
            'custom_id' => $this->custom_id,
            'custom_class' => $this->custom_class,
            'value' => $this->default,
            'validator' => $this->validator,
        ];
    }
}
