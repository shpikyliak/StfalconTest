<?php

namespace App\Service\Bank;

use App\Config\CurrencyType;
use App\Exception\ApiException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractBankApi implements CurrencyApiInterface
{
    protected array $responseData = [];
    protected string $url;

    public function __construct(protected HttpClientInterface $httpClient)
    {
    }

    /**
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function setResponseData(): void
    {
        if (!count($this->responseData)) {
            $response = $this->httpClient->request('GET', $this->url);

            if ($response->getStatusCode() !== 200) {
                throw new ApiException(sprintf(
                    'Error fetching data from API: %s (%d)',
                    $response->getContent(false),
                    $response->getStatusCode()
                ));
            }

            $this->responseData = $response->toArray();
        }
    }

    /**
     * @param  CurrencyType  $currencyType
     *
     * @return float
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCurrencyData(CurrencyType $currencyType): float
    {
        $this->setResponseData();

        return $this->getCurrencyByType($currencyType);
    }


}
