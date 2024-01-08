<?php

namespace App\Service;

interface CurrencyExchangeRatesServiceInterface
{
    /**
     * @param string $currencyExchangeRatesUrl
     */
    public function __construct(string $currencyExchangeRatesUrl);

    /**
     */
    public function setRates(): void;

    /**
     * @param string $currency
     * @param float $amount
     * @return float
     */
    public function convertToBase(string $currency, float $amount): float;

    /**
     * @param string $currency
     * @param float $amount
     * @return float
     */
    public function convertFromBase(string $currency, float $amount): float;
}
