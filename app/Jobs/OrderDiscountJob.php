<?php

namespace App\Jobs;

use App\Enums\DiscountType;
use App\Models\Discount;
use App\Models\DiscountOrder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public int $orderId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Order $order */
        $order = Order::query()->find($this->orderId);
        $orderItems = $order->items;

        //info :: order items price is over than 1000 then apply discount
        if ($order->total >= 1000) {
            $discountOrder = new DiscountOrder;
            $discountOrder->fill([
                'reason' => DiscountType::TEN_PERCENT_OVER_PRICE->value,
                'amount' => $order->total * 0.1,
                'subtotal' => $order->total - $order->total * 0.1,
            ]);
            $discountOrder->order()->associate($order);
            $discount = Discount::query()->where('name', DiscountType::TEN_PERCENT_OVER_PRICE->value)->first();
            $discountOrder->discount()->associate($discount);
            $discountOrder->save();
            $order->update(['total' => $discountOrder->subtotal]);
        }

        //info :: order items product is belongs to category 2 and quantity is over than 6 then apply discount
        $category2Items = $orderItems->filter(function ($item) {
            return $item->product->category_id === 2 && $item->quantity >= 5;
        });
        if ($category2Items->count() > 0) {
            foreach ($category2Items as $item) {
                $discountOrder = new DiscountOrder;
                $discountOrder->fill([
                    'reason' => DiscountType::ONE_FREE->value,
                    'amount' => $item->unit_price,
                    'subtotal' => $order->total - $item->unit_price,
                ]);
                $discountOrder->order()->associate($order);
                $discount = Discount::query()->where('name', DiscountType::ONE_FREE->value)->first();
                $discountOrder->discount()->associate($discount);
                $discountOrder->save();
                $order->update(['total' => $discountOrder->subtotal]);
            }
        }

        //info :: order items has 2 or more products and product category is 1 then apply discount
        $category1Items = $orderItems->filter(function ($item) {
            return $item->product->category_id === 1;
        });

        if ($category1Items->count() >= 2) {
            $lessPriceItem = $category1Items->sortBy('unit_price')->first();
            $discountOrder = new DiscountOrder;
            $discountOrder->fill([
                'reason' => DiscountType::TWENTY_PERCENT->value,
                'amount' => $lessPriceItem->unit_price * 0.2,
                'subtotal' => $order->total - ($lessPriceItem->total - $lessPriceItem->unit_price * 0.2),
            ]);
            $discountOrder->order()->associate($order);
            $discount = Discount::query()->where('name', DiscountType::TWENTY_PERCENT->value)->first();
            $discountOrder->discount()->associate($discount);
            $discountOrder->save();
            $order->update(['total' => $discountOrder->subtotal]);
        }

    }
}
