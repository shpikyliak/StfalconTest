<?php

namespace App\Command;

use App\Config\CurrencyType;
use App\Entity\CurrencyThresholdCheck;
use App\Exception\ApiException;
use App\Service\Bank\Monobank;
use App\Service\Bank\Privat;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-currency')]
class CheckCurrencyRateCommand extends Command
{
    /**
     * @param  EntityManagerInterface  $entityManager
     * @param  Monobank  $monoService
     * @param  Privat  $privatService
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Monobank $monoService,
        private Privat $privatService,
    )
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Create record for checking currency rates from PrivatBank and Monobank.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email to notify')
            ->addArgument("threshold", InputArgument::REQUIRED, "Currency threshold")
            ->addArgument('currency', InputArgument::REQUIRED, 'Email to notify');
    }

    /**
     *  This method retrieves the current exchange rates for a specified currency,
     *  validates the input arguments (email, threshold, and currency type),
     *  and saves the data into the database.
     *  A cron job periodically checks for rate changes exceeding the specified threshold
     *  by comparing the stored values with updated data from the API.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return int
     * @throws \DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->validate($input);
        }catch (InvalidArgumentException $exception){
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }

        $threshold = (float) $input->getArgument('threshold');
        $email = $input->getArgument('email');
        $currencyType = CurrencyType::tryFrom($input->getArgument('currency'));

        try {
            $rateMono = $this->monoService->getCurrencyData($currencyType);
            $ratePrivat = $this->privatService->getCurrencyData($currencyType);
        }catch (ApiException $e)
        {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        //check if email exist in db if needed
        $currencyThreshold = new CurrencyThresholdCheck(
            $email,
            $currencyType,
            $rateMono,
            $ratePrivat,
            $threshold,
            new \DateTime('now', new \DateTimeZone('UTC'))
        );

        $this->entityManager->persist($currencyThreshold);
        $this->entityManager->flush();

        $output->writeln('Threshold and email saved successfully.');

        return Command::SUCCESS;
    }

    /**
     * Arguments validation. Can use symfony validation or symfony-console-form package for more difficult validation
     *
     * @param  InputInterface  $input
     *
     * @return void
     */
    private function validate(InputInterface $input)
    {
        $threshold = $input->getArgument('threshold');

        if (!is_numeric($threshold) || (float)$threshold <= 0) {
           throw new InvalidArgumentException("Argument threshold should be positive number.");
        }

        if (!CurrencyType::tryFrom($input->getArgument('currency'))) {
            throw new InvalidArgumentException(sprintf("Argument currency should be one of %s", CurrencyType::getStringList()));
        }

        if (!filter_var($input->getArgument('email'), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Argument email incorrect");
        }

        if (!CurrencyType::tryFrom($input->getArgument('currency'))) {
            throw new InvalidArgumentException(sprintf("Argument currency should be one of %s", CurrencyType::getStringList()));
        }
    }
}
