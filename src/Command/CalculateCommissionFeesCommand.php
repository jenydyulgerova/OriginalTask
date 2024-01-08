<?php

namespace App\Command;

use App\Entity\Transaction;
use App\Service\CurrencyExchangeRatesServiceInterface;
use App\Service\DataService;
use App\Service\FeeService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:calculate-commission-fees')]
class CalculateCommissionFeesCommand extends Command
{
    /**
     * @var array<mixed>
     */
    private $clientsPeriodData = [];

    public function __construct(
        private CurrencyExchangeRatesServiceInterface $currencyExchangeRatesService,
        private DataService $dataService,
        private FeeService $feeService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('This command calculates the commission fees for the list of 
                transactions provided in the file with path [dataFile]')
            ->addArgument('dataFile', InputArgument::REQUIRED, 'Path to the data file (.CSV)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $transactions = $this->dataService->getData($input->getArgument('dataFile'));
        $result = [];

        foreach ($transactions as $transaction) {
            $currentPeriod = $this->calculatePeriodByDate($transaction->getOperationDate());
            if (!array_key_exists($currentPeriod, $this->clientsPeriodData)) {
                $this->clientsPeriodData[$currentPeriod] = [];
            }

            if (!array_key_exists($transaction->getUserId(), $this->clientsPeriodData[$currentPeriod])) {
                $this->clientsPeriodData[$currentPeriod][$transaction->getUserId()] = [
                    'sumInBaseCurrency' => 0,
                    'countTransactions' => 0
                ];
            }
            $currentClientPeriodData = &$this->clientsPeriodData[$currentPeriod][$transaction->getUserId()];

            switch ($transaction->getOperationType()) {
                case Transaction::OPERATION_TYPE_DEPOSIT:
                    $result[] = $this->feeService->calculateDepositFee(
                        (float) $transaction->getOperationAmount(),
                        $this->calculateDecimalPlaces($transaction->getOperationAmount())
                    );
                    break;

                case Transaction::OPERATION_TYPE_WITHDRAW:
                    switch ($transaction->getUserType()) {
                        case Transaction::USER_TYPE_PRIVATE:
                            if (
                                $currentClientPeriodData['countTransactions'] >= 3 ||
                                $currentClientPeriodData['sumInBaseCurrency'] >= 1000
                            ) {
                                $feeBaseAmount = $transaction->getOperationAmount();
                            } else {
                                $transactionAmountInBaseCurrency = $this->currencyExchangeRatesService->convertToBase(
                                    $transaction->getOperationCurrency(),
                                    (float) $transaction->getOperationAmount()
                                );

                                $restFreeAmountInBaseCurrency = max(
                                    0,
                                    1000 - $currentClientPeriodData['sumInBaseCurrency']
                                );

                                $feeBaseAmountInBaseCurrency = max(
                                    0,
                                    $transactionAmountInBaseCurrency - $restFreeAmountInBaseCurrency
                                );

                                $feeBaseAmount = $this->currencyExchangeRatesService->convertFromBase(
                                    $transaction->getOperationCurrency(),
                                    $feeBaseAmountInBaseCurrency
                                );
                            }

                            $result[] = $this->feeService->calculateWithdrawPrivateFee(
                                (float) $feeBaseAmount,
                                $this->calculateDecimalPlaces($transaction->getOperationAmount())
                            );
                            break;

                        case Transaction::USER_TYPE_BUSINESS:
                            $result[] = $this->feeService->calculateWithdrawBusinessFee(
                                (float) $transaction->getOperationAmount(),
                                $this->calculateDecimalPlaces($transaction->getOperationAmount())
                            );
                            break;
                    }

                    $currentClientPeriodData['sumInBaseCurrency'] += $this->currencyExchangeRatesService->convertToBase(
                        $transaction->getOperationCurrency(),
                        (float) $transaction->getOperationAmount()
                    );
                    $currentClientPeriodData['countTransactions']++;

                    break;

                default:
                    $result[] = 'Error: Not supported operation type';
                    break;
            }
        }

        foreach ($result as $row) {
            $output->writeln($row);
        }

        return Command::SUCCESS;
    }

    private function calculatePeriodByDate(\DateTime $date): string
    {
        return $date->sub(new \DateInterval("P" . ((int) $date->format("N") - 1) . "D"))->format("Y-m-d");
    }

    private function calculateDecimalPlaces(string $amount): int
    {
        $amountArr = explode(".", $amount);
        if (array_key_exists(1, $amountArr)) {
            return strlen($amountArr[1]);
        }
        return 0;
    }
}
