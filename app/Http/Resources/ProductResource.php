<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'category' => $this->category,
      'unit' => $this->unit,
      'price' => $this->price,
      'image' => $this->image,
      'weight' => $this->when($this->weight !== null, $this->weight),
      'discount' => $this->when($this->discount !== null, $this->discount),
      'discount_price' => $this->when($this->discount_price !== null, $this->discount_price),
    ];
  }
}
