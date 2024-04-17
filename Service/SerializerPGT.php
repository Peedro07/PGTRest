<?php

namespace PGTRest\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ReflectionClass;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class SerializerPGT
{

    public function serializer(): Serializer
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        return new Serializer(
            [
                new DateTimeNormalizer(['datetime_format' => 'Y-m-d']),
                new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)
            ],
            ['json' => new JsonEncoder()]
        );
    }

    private function nameEntity(object $entity): string
    {
        $reflectionClass = new ReflectionClass($entity);

        $className = ltrim(strtolower(preg_replace('/([A-Z])/', '_$1', $reflectionClass->getShortName())), '_');

        while ($parentClass = $reflectionClass->getParentClass()) {
            $className = ltrim(strtolower(preg_replace('/([A-Z])/', '_$1', $parentClass->getShortName())), '_');
            $reflectionClass = $parentClass;
        }

        return $className;
    }

    public function normalizeData($array, $groups): JsonResponse|array
    {

        $result = $this->serializeData($array, $groups);
        foreach ($result as $key => $value) {
            if (is_array($value) && count($value) === 1) {
                if (isset($value[$key])) {
                    $data = $value[$key];
                } else {
                    $data = $value[0];
                }
                $result[$key] = $data;
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
                if (is_array($item)) {
                    if (count($item) > 0 && is_object($item[0])) {
                        $result[$this->nameEntity($item[0])] = $this->serializeData($item, $groups);
                    } else {
                        $result[$key] = $item;
                    }
                } else {
                    $result[$key] = $item;
                }
            }
        }
        return $result;
    }
}