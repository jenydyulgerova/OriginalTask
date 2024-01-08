<?php

namespace App\Service;

class CurrencyExchangeRatesOfflineService implements CurrencyExchangeRatesServiceInterface
{
    /**
     * @var array<mixed>
     */
    private $rates;

    /**
     * @param string $currencyExchangeRatesData
     */
    public function __construct(private string $currencyExchangeRatesData)
    {
        $this->setRates();
    }

    public function setRates(): void
    {
        $this->rates = json_decode($this->currencyExchangeRatesData, true);
    }

    /**
     * @return array<mixed>
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    public function convertToBase(string $currency, float $amount): float
    {
        if (array_key_exists($currency, $this->rates)) {
            return $amount / $this->rates[$currency];
        } else {
            throw new \Exception("Unsupported currency");
        }
    }

    public function convertFromBase(string $currency, float $amount): float
    {
        if (array_key_exists($currency, $this->rates)) {
            return $amount * $this->rates[$currency];
        } else {
            throw new \Exception("Unsupported currency");
        }
    }
}
