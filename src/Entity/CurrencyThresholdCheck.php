<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Config\CurrencyType;

#[Orm\Entity]
class CurrencyThresholdCheck
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    /**
     * @param  string  $email
     * @param  CurrencyType  $currency
     * @param  float  $rateMono
     * @param  float  $ratePrivat
     * @param  float  $threshold
     * @param  DateTimeInterface  $createdAt
     */
    public function __construct(
        #[ORM\Column(type: "string", length: 255)]
        private string $email,

        #[ORM\Column(type: 'string', enumType: CurrencyType::class)]
        private CurrencyType $currency,

        #[ORM\Column(type: 'float')]
        private float $rateMono,

        #[ORM\Column(type: 'float')]
        private float $ratePrivat,

        #[ORM\Column(type: 'float')]
        private float $threshold,

        #[ORM\Column(type: 'datetime')]
        private DateTimeInterface $createdAt
    ){

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param  string  $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return CurrencyType
     */
    public function getCurrency(): CurrencyType
    {
        return $this->currency;
    }

    /**
     * @param  CurrencyType  $currency
     *
     * @return void
     */
    public function setCurrency(CurrencyType $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getRateMono(): float
    {
        return $this->rateMono;
    }

    /**
     * @param  float  $rateMono
     *
     * @return void
     */
    public function setRateMono(float $rateMono): void
    {
        $this->rateMono = $rateMono;
    }

    /**
     * @return float
     */
    public function getRatePrivat(): float
    {
        return $this->ratePrivat;
    }

    /**
     * @param  float  $ratePrivat
     *
     * @return void
     */
    public function setRatePrivat(float $ratePrivat): void
    {
        $this->ratePrivat = $ratePrivat;
    }

    /**
     * @return float
     */
    public function getThreshold(): float
    {
        return $this->threshold;
    }

    /**
     * @param  float  $threshold
     *
     * @return void
     */
    public function setThreshold(float $threshold): void
    {
        $this->threshold = $threshold;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param  DateTimeInterface  $createdAt
     *
     * @return void
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }




}
