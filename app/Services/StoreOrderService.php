<?php

namespace App\Services;

use App\Jobs\OrderDiscountJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class StoreOrderService
{
    public function handle(array $data): bool
    {
        try {
            $order = new Order;
            $order->customer()->associate($data['customer_id']);
            $this->saveOrderItems($order, $data['items']);
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
        return true;
    }

    /**
     * @param Order $order
     * @param array $items
     * @return void
     * @throws \Throwable
     */
    private function saveOrderItems(Order $order, array $items): void
    {
        $data = [];
        foreach ($items as $item) {
            /** @var Product $product */
            $product = Product::query()->findOrFail($item['product_id']);
            if ($product->stock < $item['quantity']) {
                //info:: Log is a laravel helper function
                Log::alert('Product stock is not enough');
                throw new ('Product stock is not enough for '. $product->name);
            }
            $total = $item['quantity'] * $product->price;
            $item = array_merge(['unit_price' => $product->price, 'total' => $total], $item);
            $data[] = $item;
        }

        $order->save();
        $order->items()->createMany($data);
        $order->update(['total' => $order->items->sum('total')]);

        OrderDiscountJob::dispatch($order->id);
    }

}
