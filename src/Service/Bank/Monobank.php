<?php

namespace App\Service\Bank;

use App\Config\CurrencyType;
use App\Exception\ApiException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Monobank extends AbstractBankApi
{
    /**
     * @var string
     */
    protected string $url = "https://api.monobank.ua/bank/currency";

    /**
     * @param  HttpClientInterface  $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @param  CurrencyType  $currencyType
     *
     * @return float
     */
    public function getCurrencyByType(CurrencyType $currencyType): float
    {
        foreach ($this->responseData as $key => $currenyInfo){
            if ($currenyInfo['currencyCodeA'] === $currencyType->isoCode()) {
                //also can get rateBuy, if needed. Can also be added as argument to command
                return $currenyInfo['rateSell'];
            }
        }

        throw new ApiException("Mono. Incorrect response structure");
    }
}
