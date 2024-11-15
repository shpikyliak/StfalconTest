<?php

namespace App\Service\Notify\Email;
use Symfony\Component\Mime\Email;

class EmailFactory
{
    /**
     * @param  string  $to
     * @param  string  $content
     *
     * @return Email
     */
    public function createCurrencyUpdateEmail(string $to, string $content): Email
    {
        return (new Email())
            ->from('noreply@yourdomain.com') //better get this email somewhere from settings
            ->to($to)
            ->subject('Currency rate is changed!') //probably better get from some settings
            ->html($content);
    }
}
