<?php

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Discount::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->index()->constrained()->cascadeOnDelete();
            $table->text('reason');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_orders');
    }
};
