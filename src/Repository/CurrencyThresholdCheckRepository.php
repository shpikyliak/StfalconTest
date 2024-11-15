<?php

namespace App\Repository;

use App\Config\CurrencyType;
use App\Entity\CurrencyThresholdCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CurrencyThresholdCheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry  $registry)
    {
        parent::__construct($registry, CurrencyThresholdCheck::class);
    }

    /**
     * @return array
     */
    public function findUniqueCurrencies(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.currency')
            ->groupBy('c.currency')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  float  $monoRate
     * @param  float  $privatRate
     * @param  CurrencyType  $currencyType
     *
     * @return array
     */
    public function findAllAboveThreshold(float $monoRate, float $privatRate, CurrencyType $currencyType): array
    {
        return $this->createQueryBuilder('c')
            ->where('ABS(c.rateMono - :rateMono) > c.threshold')
            ->orWhere('ABS(c.ratePrivat - :ratePrivat) > c.threshold')
            ->andWhere('c.currency = :currency')
            ->setParameter('rateMono', $monoRate)
            ->setParameter('ratePrivat', $privatRate)
            ->setParameter('currency', $currencyType)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param  CurrencyThresholdCheck  $currencyThresholdCheck
     *
     * @return void
     */
    public function deleteEntity(CurrencyThresholdCheck $currencyThresholdCheck)
    {
        //example method, probably better to change status, or delete all in one query (using Query builder)
        $em = $this->getEntityManager();
        $em->remove($currencyThresholdCheck);
        $em->flush();
    }
}
