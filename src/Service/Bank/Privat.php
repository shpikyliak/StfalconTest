<?php

namespace App\Service\Bank;

use App\Config\CurrencyType;
use App\Exception\ApiException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Privat extends AbstractBankApi
{
    /**
     * @var string
     */
    protected string $url = "https://api.privatbank.ua/p24api/pubinfo";

    /**
     * @param  CurrencyType  $currencyType
     *
     * @return float
     */
    public function getCurrencyByType(CurrencyType $currencyType): float
    {
        foreach ($this->responseData as $key => $currencyInfo) {
            if ($currencyInfo['ccy'] == $currencyType->value) {

                return $currencyInfo['sale'];
            }
        }

        throw new ApiException("Privat. Incorrect response structure");
    }
}
