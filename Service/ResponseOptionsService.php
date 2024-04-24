<?php

namespace PGTRest\Service;

class ResponseOptionsService
{
    private int $statusCode;

    private array $groups;

    private string $formatDate;

    public function __construct()
    {
        $this->statusCode = 200;
        $this->groups = [];
        $this->formatDate = "Y-m-d H:i:s";
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    public function getFormatDate(): string
    {
        return $this->formatDate;
    }

    public function setFormatDate(string $formatDate): void
    {
        $this->formatDate = $formatDate;
    }

}