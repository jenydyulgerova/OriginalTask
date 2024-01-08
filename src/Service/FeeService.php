<?php

namespace App\Service;

class FeeService
{
    public const DEPOSIT_FEE_PERCENTAGE = 0.03;
    public const WITHDRAW_BUSINESS_FEE_PERCENTAGE = 0.5;
    public const WITHDRAW_PRIVATE_FEE_PERCENTAGE = 0.3;

    public function calculateDepositFee(float $amount, int $decimalPlaces = 2): string
    {
        $feeAmount = (self::DEPOSIT_FEE_PERCENTAGE / 100) * $amount;

        return $this->returnFee($feeAmount, $decimalPlaces);
    }

    public function calculateWithdrawBusinessFee(float $amount, int $decimalPlaces = 2): string
    {
        $feeAmount = (self::WITHDRAW_BUSINESS_FEE_PERCENTAGE / 100) * $amount;
        return $this->returnFee($feeAmount, $decimalPlaces);
    }

    public function calculateWithdrawPrivateFee(float $amount, int $decimalPlaces = 2): string
    {
        $feeAmount = (self::WITHDRAW_PRIVATE_FEE_PERCENTAGE / 100) * $amount;
        return $this->returnFee($feeAmount, $decimalPlaces);
    }

    private function returnFee(float $feeAmount, int $decimalPlaces): string
    {
        if ($decimalPlaces == 0) {
            return sprintf("%01." . $decimalPlaces . "f", ceil($feeAmount));
        } else {
            return sprintf(
                "%01." . $decimalPlaces . "f",
                round(
                    ($feeAmount + 4 / pow(10, $decimalPlaces + 1)),
                    $decimalPlaces
                )
            );
        }
    }
}
