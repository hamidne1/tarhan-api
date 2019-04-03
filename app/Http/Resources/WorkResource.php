<?php
/**
 * Created by PhpStorm.
 * User: EMAD
 * Date: 4/1/2019
 * Time: 6:29 PM
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}