<?php

namespace App\Service;

use App\Entity\Transaction;

class DataService
{
    /**
     * @param string $filePath
     * @return Transaction[]
     */
    public function getData(string $filePath): array
    {
        $data = array_map('str_getcsv', file($filePath));

        $transactions = [];

        foreach ($data as $row) {
            $transactions[] = new Transaction(
                new \DateTime($row[0]),
                (int) $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5]
            );
        }

        return $transactions;
    }
}
