<?php

namespace PGTRest\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ReflectionClass;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class SerializerPGT
{

    public function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);

        return new Serializer([new DateTimeNormalizer(['datetime_format' => 'Y-m-d']), $normalizer]);
    }

    private function nameEntity(object $entity): string
    {
        return ltrim(strtolower(preg_replace('/([A-Z])/', '_$1', (new ReflectionClass($entity))->getShortName())), '_');
    }

    public function normalizeData($array, $groups): JsonResponse|array
    {

        $result = $this->serializeData($array, $groups);

        foreach ($result as $key => $value) {
            if (is_array($value) && count($value) === 1) {
                $result[$key] = $value[0];
            }
        }

        return $result;
    }

    public function serializeData($array, $groups): JsonResponse|array
    {
        $spgt = new SerializerPGT();
        $serializer = $spgt->serializer();

        $result = [];
        foreach ($array as $key => $item) {
            if (is_object($item)) {
                try {
                    $normalizedItem = $serializer->normalize($item, null, ['groups' => $groups]);
                    $result[$this->nameEntity($item)][] = $normalizedItem;
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], 500);
                } catch (ExceptionInterface $e) {
                    return new JsonResponse(['error' => $e->getMessage()], 500);
                }
            } else {
                $result[$key] = $item;
            }
        }
        return $result;
    }
}