<?php

namespace App\Entity;

class Transaction
{
    public const OPERATION_TYPE_DEPOSIT = 'deposit';
    public const OPERATION_TYPE_WITHDRAW = 'withdraw';
    public const USER_TYPE_PRIVATE = 'private';
    public const USER_TYPE_BUSINESS = 'business';

    /**
     * @param \DateTime $operationDate
     * @param int $userId
     * @param string $userType
     * @param string $operationType
     * @param string $operationAmount
     * @param string $operationCurrency
     */
    public function __construct(
        private \DateTime $operationDate,
        private int $userId,
        private string $userType,
        private string $operationType,
        private string $operationAmount,
        private string $operationCurrency
    ) {
    }

    /**
     * @return \DateTime
     */
    public function getOperationDate(): \DateTime
    {
        return $this->operationDate;
    }

    /**
     * @param \DateTime $operationDate
     * @return self
     */
    public function setOperationDate(\DateTime $operationDate): self
    {
        $this->operationDate = $operationDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     * @return self
     */
    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     * @return self
     */
    public function setOperationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperationAmount(): string
    {
        return $this->operationAmount;
    }

    /**
     * @param string $operationAmount
     * @return self
     */
    public function setOperationAmount(string $operationAmount): self
    {
        $this->operationAmount = $operationAmount;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperationCurrency(): string
    {
        return $this->operationCurrency;
    }

    /**
     * @param string $operationCurrency
     * @return self
     */
    public function setOperationCurrency(string $operationCurrency): self
    {
        $this->operationCurrency = $operationCurrency;

        return $this;
    }
}
