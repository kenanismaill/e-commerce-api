<?php

namespace App\Http\Resources\Api\V1\OrderItem;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'productId' => $this->product_id,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'total' => $this->total,
        ];
    }
}
