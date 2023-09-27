<?php


class ResponseOptionsService
{
    private array $responseOptions;

    public function setOptions($statusCode, $groups): void
    {
        $this->responseOptions = ['statusCode' => $statusCode, 'groups' => $groups];
    }

    public function getOptions(): array
    {
        return $this->responseOptions;
    }
}