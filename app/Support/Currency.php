<?php

namespace App\Support;

class Currency
{
    public static function symbolFor(?string $nameOrCode): string
    {
        if (!$nameOrCode) return '';
        $key = strtoupper(trim($nameOrCode));
        $map = config('currency.symbols', []);
        return $map[$key] ?? '';
    }
}
