<?php

namespace PGTRest\Controller;

use PGTRest\Service\ResponseOptionsService;
use PGTRest\Service\SerializerPGT;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


abstract class AbstractPGTRest extends AbstractController
{
    private ResponseOptionsService $responseOptionsService;

    public function __construct(ResponseOptionsService $responseOptionsService)
    {
        $this->responseOptionsService = $responseOptionsService;
    }

    protected function view(array $data = [], $statusCode = null, $groups = null): JsonResponse
    {
        $statusCode = $statusCode ?? $this->responseOptionsService->getStatusCode();
        $groups = $groups ?? $this->responseOptionsService->getGroups();
        $formDate = $this->responseOptionsService->getFormatDate();
        $serializerPGT = new SerializerPGT();
        $json = $serializerPGT->serializeData($data, $groups, $formDate);

        return new JsonResponse($json, $statusCode);
    }


    public function jsonDecode(Request $request, $associative = true)
    {
        return json_decode($request->getContent(), $associative);
    }


}

