<?php

namespace App\Http\Resources\Api\V1\Order;

use App\Http\Resources\Api\V1\OrderItem\OrderItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customer_id,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'total' => $this->whenLoaded('items', function () {
                return number_format((float)$this->items->sum('total'), 2, '.', '');
            }),
        ];
    }
}
