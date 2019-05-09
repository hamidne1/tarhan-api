<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'label' => $this->label,
            'slug' => $this->slug,
            'catalog_id' => $this->catalog_id,
            'tariffs' => TariffResource::collection($this->whenLoaded('tariffs')),
            'contexts' => ContextResource::collection($this->whenLoaded('contexts')),
            'widgets' => WidgetResource::collection($this->whenLoaded('widgets')),
        ];
    }
}
