<?php

if (! function_exists('pkr')) {
    function pkr(int|float $amount): string
    {
        return 'PKR ' . number_format($amount, 2);
    }
}

if (! function_exists('stockStatus')) {
    function stockStatus(int $current, int $reorder): string
    {
        if ($current === 0 && $reorder > 0) return 'Out of Stock';
        if ($current > 0 && $current < $reorder) return 'Low Stock';
        return 'In Stock';
    }
}
