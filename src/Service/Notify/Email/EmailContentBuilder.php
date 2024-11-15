<?php

namespace App\Service\Notify\Email;
use App\Entity\CurrencyThresholdCheck;
use Twig\Environment;
class EmailContentBuilder
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @param  Environment  $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param  CurrencyThresholdCheck  $currencyThresholdCheck
     * @param  float  $monoRate
     * @param  float  $privatRate
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function buildEmailContent(CurrencyThresholdCheck $currencyThresholdCheck, float $monoRate, float $privatRate): string
    {
        return $this->twig->render('emails/currency_update.html.twig', compact(['currencyThresholdCheck', 'monoRate', 'privatRate']));
    }
}
