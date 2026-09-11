<?php

namespace App\Support;

class Money
{
    public static function naira(int|float $amount): string
    {
        return '₦'.number_format((float) $amount);
    }
}
