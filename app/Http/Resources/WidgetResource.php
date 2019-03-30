<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'category_id' => $this->category_id,
            'col' => $this->col,
            'group' => $this->group,
            'title' => $this->title,
            'alt' => $this->alt,
            'href' => $this->href,
            'src' => $this->src
        ];
    }
}
