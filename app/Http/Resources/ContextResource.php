<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer id
 * @property integer page_id
 * @property integer parent_id
 * @property integer category_id
 * @property string slug
 * @property string icon
 * @property string href
 * @property string value
 */
class ContextResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'parent_id' => $this->parent_id,
            'category_id' => $this->category_id,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'href' => $this->href,
            'value' => $this->value,
        ];
    }
}
