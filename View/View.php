<?php

namespace PgtRest\View;

final class View
{
    private array $data;
    private int $statusCode;

    public function __construct($data = null, ?int $statusCode = null, array $headers = [])
    {
        $this->setData($data);
        $this->setStatusCode($statusCode);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
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

}