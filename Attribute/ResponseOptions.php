<?php

namespace PGTRest\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class ResponseOptions
{
    private int $statusCode;
    private array $groups;

    public function __construct(int $statusCode, array $groups = [])
    {
        $this->statusCode = $statusCode;
        $this->groups = $groups;
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
}
