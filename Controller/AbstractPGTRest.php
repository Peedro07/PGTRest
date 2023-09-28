<?php

namespace PGTRest\Controller;

use PGTRest\Service\ResponseOptionsService;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;


class AbstractPGTRest extends AbstractController
{
    private ResponseOptionsService $responseOptionsService;

    public function __construct(ResponseOptionsService $responseOptionsService)
    {
        $this->responseOptionsService = $responseOptionsService;
    }

    protected function view(array $data = [], $statusCode = null, $groups = null): JsonResponse
    {
        $responseOptions = $this->responseOptionsService->getOptions();
        $code = $statusCode ?? $responseOptions['statusCode'];
        $groupsSerialize = $groups ?? $responseOptions['groups'];

        $serializer = $this->serializer();
        if (empty($data)) {
            return new JsonResponse(null, $code);
        }

        $jsons = $this->normalizeArray($data, $serializer, $groupsSerialize);


        return new JsonResponse(array_merge($jsons), $code);
    }

    protected function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        return new Serializer([new DateTimeNormalizer(['datetime_format' => 'Y-m-d']), $normalizer]);
    }

    private function nameEntity(object $entity): string
    {
        return ltrim(strtolower(preg_replace('/([A-Z])/', '_$1', (new ReflectionClass($entity))->getShortName())), '_');
    }

    protected function jsonDecode(Request $request, $associative = true)
    {
        return json_decode($request->getContent(), $associative);
    }

    protected function normalizeArray($array, $serializer, $groupsSerialize): JsonResponse|array
    {
        $result = [];

        foreach ($array as $key => $item) {
            if (is_object($item)) {
                try {
                    $normalizedItem = $serializer->normalize($item, null, ['groups' => $groupsSerialize]);
                    $result[$this->nameEntity($item)][] = $normalizedItem;
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], 500);
                } catch (ExceptionInterface $e) {
                    return new JsonResponse(['error' => $e->getMessage()], 500);
                }
            } elseif (is_array($item)) {
                $normalizedItem = $this->normalizeArray($item, $serializer, $groupsSerialize);
                if (!empty($normalizedItem)) {
                    $result = array_merge_recursive($result, $normalizedItem);
                }
            } else {
                $result[$key] = $item;
            }
        }

        foreach ($result as $key => $value) {
            if (is_array($value) && count($value) === 1) {
                $result[$key] = $value[0];
            }
        }

        return $result;
    }


}

