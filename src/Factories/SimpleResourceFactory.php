<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Factories;

use CarloNicora\JsonApi\Objects\Link;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes\Resource;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes\ResourceDetail;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceDetailType;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceValueTransformation;
use Exception;
use ReflectionObject;

class SimpleResourceFactory
{
    /** @var EncrypterInterface|null  */
    private static ?EncrypterInterface $encrypter=null;

    /**
     * @param EncrypterInterface|null $encrypter
     * @return void
     */
    public static function initialise(
        ?EncrypterInterface $encrypter,
    ): void
    {
        self::$encrypter = $encrypter;
    }

    /**
     * @param mixed $object
     * @return ResourceObject
     * @throws Exception
     */
    public static function buildResource(
        mixed $object,
    ): ResourceObject
    {
        $reflection = new ReflectionObject($object);
        /** @var Resource $resource */
        $resource = $reflection->getAttributes(Resource::class)[0]->newInstance();
        $response = new ResourceObject($resource->getType());
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(ResourceDetail::class);
            if (!empty($attributes)) {
                /** @var ResourceDetail $resourceDetail */
                $resourceDetail = $attributes[0]->newInstance();
                if (!array_key_exists('name', $attributes[0]->getArguments())) {
                    $resourceDetail->setName($property->getName());
                }

                $additionalValue = null;
                if (array_key_exists('linkProperty', $attributes[0]->getArguments())) {
                    $additionalProperty = $reflection->getProperty($attributes[0]->getArguments()['linkProperty']);
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $additionalProperty->setAccessible(true);
                    $additionalValue = $additionalProperty->getValue($object);
                }

                /** @noinspection PhpExpressionResultUnusedInspection */
                $property->setAccessible(true);
                $value = $property->getValue($object);

                self::setProperty(
                    resource: $response,
                    details: $resourceDetail,
                    additionalValue: $additionalValue,
                    value: $value,
                );
            }
        }

        return $response;
    }

    /**
     * @param ResourceObject $resource
     * @param ResourceDetail $details
     * @param mixed $additionalValue
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    private static function setProperty(
        ResourceObject $resource,
        ResourceDetail $details,
        mixed $additionalValue,
        mixed $value,
    ): void
    {
        switch ($details->getTransformation()){
            case ResourceValueTransformation::Encryption:
                if (self::$encrypter !== null){
                    $value = self::$encrypter->encryptId($value);
                }
                break;
            case ResourceValueTransformation::IntDateTime:
                $value = ($value !== null ? date('Y-m-d H:i:s', $value) : null);
                break;
        }


        switch ($details->getType()){
            case ResourceDetailType::Id:
                $resource->id = $value;
                $resource->links->add(
                    new Link(
                        name: 'self',
                        href: $additionalValue . $value,
                    )
                );
                break;
            case ResourceDetailType::Attribute:
                $resource->attributes->add(
                    name: $details->getName(),
                    value: $value,
                );
                break;
            case ResourceDetailType::Meta:
                $resource->meta->add(
                    name: $details->getName(),
                    value: $value,
                );
                break;
            case ResourceDetailType::Link:
                if ($value !== null) {
                    $resource->links->add(
                        new Link(
                            name: $details->getName(),
                            href: $additionalValue . $value,
                        )
                    );
                }
                break;
            case ResourceDetailType::RelationshipLink:
                if ($value !== null) {
                    $resource->relationship($details->getName())->links->add(
                        new Link(
                            name: 'related',
                            href: $additionalValue . $value,
                        )
                    );
                }
                break;
        }
    }
}