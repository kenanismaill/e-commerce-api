<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

enum DiscountType: string
{
    case TEN_PERCENT_OVER_PRICE = 'TEN PERCENT OVER PRICE';
    case ONE_FREE = 'BUY_5_GET_1';
    case TWENTY_PERCENT = 'TWO_PRODUCTS_FROM_CATEGORY_WITH_ID_1';
}
