<?php

namespace App\Service;

class CurrencyExchangeRatesOnlineService implements CurrencyExchangeRatesServiceInterface
{
    /**
     * @var array<mixed>
     */
    private $rates;

    /**
     * @param string $currencyExchangeRatesUrl
     */
    public function __construct(private string $currencyExchangeRatesUrl)
    {
        $this->setRates();
    }

    public function setRates(): void
    {
        $content = file_get_contents($this->currencyExchangeRatesUrl);
        $data = json_decode($content, true);

        $data['rates'][$data['base']] = 1;

        $this->rates = $data['rates'];
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
        ;
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
