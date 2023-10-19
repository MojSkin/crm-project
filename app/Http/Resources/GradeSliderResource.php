<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeSliderResource extends JsonResource
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
            'tab_index' => $this->tab_index,
            'image' => $this->image,
            'title' => $this->title,
            'title_align' => $this->title_align,
            'title_position' => $this->title_position,
            'title_size' => $this->title_size,
            'title_color' => $this->title_color,
            'description' => $this->description,
            'description_align' => $this->description_align,
            'description_position' => $this->description_position,
            'description_size' => $this->description_size,
            'description_color' => $this->description_color,
            'cta_text' => $this->cta_text,
            'cta_link' => $this->cta_link,
            'cta_target' => $this->cta_target === '_self' ? 'همین صفحه' : 'تب جدید',
            'cta_position' => $this->cta_position,
            'cta_size' => $this->cta_size,
            'cta_color' => $this->cta_color,
            'cta_light' => $this->cta_light ? 'بلی' : 'خیر',
            'cta_bordered' => $this->cta_bordered ? 'بلی' : 'خیر',
            'cta_rounded' => $this->cta_rounded ? 'بلی' : 'خیر',
            'cta_block' => $this->cta_block ? 'بلی' : 'خیر',
        ];
    }
}
