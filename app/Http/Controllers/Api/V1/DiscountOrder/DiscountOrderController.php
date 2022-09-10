<?php

namespace App\Http\Controllers\Api\V1\DiscountOrder;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DiscountOrder\DiscountOrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DiscountOrderController extends Controller
{
    public function index(Request $request, Order $order): DiscountOrderResource
    {
        // info:: we can apply filters here
        return  new DiscountOrderResource($order->load('discounts'));
    }
}
