<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed title
 * @property mixed sub_title
 * @property mixed price
 * @property mixed discount
 * @property mixed icon
 * @property mixed category_id
 */
class TariffResource extends JsonResource {
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
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'price' => $this->price,
            'discount' => $this->discount,
            'icon' => $this->icon,
            'category_id' => $this->category_id
        ];
    }
}
