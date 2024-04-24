<?php

namespace PGTRest\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class ResponseOptions
{
    private int $statusCode;
    private array $groups;
    private string $formatDate;

    public function __construct(int $statusCode, array $groups = [], string $formatDate = "Y-m-d H:i:s")
    {
        $this->statusCode = $statusCode;
        $this->groups = $groups;
        $this->formatDate = $formatDate;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getFormatDate(): string
    {
        return $this->formatDate;
    }


}
