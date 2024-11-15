<?php

namespace App\Service\Notify;

use App\Entity\CurrencyThresholdCheck;

interface NotifierInterface
{
    /**
     * @param  CurrencyThresholdCheck  $currencyThreshold
     * @param  float  $monoRate
     * @param  float  $privatRate
     *
     * @return mixed
     */
    public function sendCurrencyThresholdNotify(CurrencyThresholdCheck $currencyThreshold, float $monoRate, float $privatRate);
}
