<?php

namespace App\Config;

enum CurrencyType: string
{
    case Usd = 'USD';
    case Eur = 'EUR';

    public function isoCode() {
        return match($this) {
            self::Usd => 840,
            self::Eur => 978
        };
    }

    static function getStringList(): string
    {
        return  implode(', ', array_column(self::cases(), 'value'));
    }
}
