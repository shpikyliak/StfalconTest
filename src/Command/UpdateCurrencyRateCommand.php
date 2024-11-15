<?php

namespace App\Command;

use App\Repository\CurrencyThresholdCheckRepository;
use App\Service\Bank\Monobank;
use App\Service\Bank\Privat;
use App\Service\Notify\Email\EmailNotifier;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:update-currency')]
class UpdateCurrencyRateCommand extends Command
{
    /**
     * @param  LoggerInterface  $logger
     * @param  Monobank  $monoService
     * @param  Privat  $privatService
     * @param  CurrencyThresholdCheckRepository  $currencyThresholdCheckRepository
     * @param  EmailNotifier  $emailNotifier
     */
    public function __construct(
        private LoggerInterface $logger,
        private Monobank $monoService,
        private Privat $privatService,
        private CurrencyThresholdCheckRepository $currencyThresholdCheckRepository,
        private EmailNotifier $emailNotifier,

    ){
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Check currency rates from PrivatBank and Monobank and notify if threshold exceeded.');
    }

    /**
     * This method represents a cron job that periodically checks for significant currency rate changes.
     *
     *  - It retrieves a list of unique currencies from the database that have active threshold checks.
     *  - For each currency, it fetches the latest exchange rates from MonoBank and PrivatBank APIs.
     *  - It identifies users whose saved thresholds are exceeded by comparing the current rates with the stored thresholds.
     *  - For each user exceeding the threshold, it sends a notification email and removes the corresponding record from the database.
     *
     *  This ensures that users are promptly notified when their specified currency rate conditions are met.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $currenciesToLook = $this->currencyThresholdCheckRepository->findUniqueCurrencies();

        foreach ($currenciesToLook as $currencyToLook) {
            $monoRate = $this->monoService->getCurrencyData($currencyToLook['currency']);
            $privatRate = $this->privatService->getCurrencyData($currencyToLook['currency']);
            $toNotifyAboutChange = $this->currencyThresholdCheckRepository->findAllAboveThreshold($monoRate, $privatRate, $currencyToLook['currency']);

            foreach ($toNotifyAboutChange as $toNotify) {
                $this->emailNotifier->sendCurrencyThresholdNotify($toNotify, $monoRate, $privatRate);
                $this->currencyThresholdCheckRepository->deleteEntity($toNotify);
            }
        }

        return Command::SUCCESS;
    }

}
