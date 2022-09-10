<?php

namespace App\Http\Resources\Api\V1\Discount;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'discountReason' => $this->reason,
            'discountAmount' => $this->amount,
            'subtotal' => $this->subtotal,
        ];
    }
}
