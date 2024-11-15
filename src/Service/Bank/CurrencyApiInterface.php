<?php

namespace App\Service\Bank;

use App\Config\CurrencyType;

interface CurrencyApiInterface
{
    /**
     * @param  CurrencyType  $currencyType
     *
     * @return float
     */
    public function getCurrencyByType(CurrencyType $currencyType): float;
}
