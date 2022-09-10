<?php

namespace App\Http\Resources\Api\V1\DiscountOrder;

use App\Http\Resources\Api\V1\Discount\DiscountResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountOrderResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'orderId' => $this->id,
            'discount' => DiscountResource::collection($this->whenLoaded('discounts')),
            'totalDiscount' => $this->whenLoaded('discounts', function () {
                return number_format((float)$this->discounts->sum('amount'), 2, '.', '');
            }),
            'discountedTotal' => $this->whenLoaded('discounts', function () {
                return $this->discounts->min('subtotal');
            }),
        ];
    }
}
