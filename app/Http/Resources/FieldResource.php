<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer id
 * @property string title
 * @property string icon
 */
class FieldResource extends JsonResource
{
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
            'icon' => $this->icon

        ];
    }

}