<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer id
 * @property string slug
 */
class PageResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'widgets' => WidgetResource::collection($this->whenLoaded('widgets')),
            'contexts' => ContextResource::collection($this->whenLoaded('contexts')),
        ];
    }
}
