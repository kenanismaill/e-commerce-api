<?php

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (DiscountType::cases() as $discountType) {
            Discount::query()->create([
                'name' => $discountType
            ]);
        }
    }
}
