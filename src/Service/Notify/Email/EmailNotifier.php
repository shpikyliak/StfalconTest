<?php

namespace App\Service\Notify\Email;

use App\Entity\CurrencyThresholdCheck;
use App\Service\Notify\NotifierInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private EmailFactory $emailFactory,
        private EmailContentBuilder $emailContentBuilder,
        private MailerInterface $mailer,
    )
    {
    }

    /**
     * @param  CurrencyThresholdCheck  $currencyThreshold
     * @param  float  $monoRate
     * @param  float  $privatRate
     *
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendCurrencyThresholdNotify(CurrencyThresholdCheck $currencyThreshold, float $monoRate, float $privatRate)
    {
        $email = $this->emailFactory->createCurrencyUpdateEmail( $currencyThreshold->getEmail(), $this->emailContentBuilder->buildEmailContent($currencyThreshold, $monoRate, $privatRate));
        $this->mailer->send($email);
    }

}
