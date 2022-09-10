<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Http\Resources\Api\V1\Order\OrderResource;
use App\Models\Order;
use App\Services\StoreOrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
       $orders = Order::query()->with('items');
       return Orderresource::collection($orders->get());
    }


    public function store(StoreOrderRequest $request)
    {
        (new StoreOrderService())->handle($request->validated());
    }


    public function destroy(Order $order)
    {
        // if we want to delete order we need to delete all related items and discounts
        $order->items()->delete();
        $order->discounts()->delete();
        $order->delete();
    }

}
