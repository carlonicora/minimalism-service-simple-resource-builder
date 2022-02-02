<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Factories;

use CarloNicora\JsonApi\Objects\Link;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes\Resource;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes\ResourceDetail;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceDetailType;
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
        $response = new ResourceObject();

        $reflection = new ReflectionObject($object);
        /** @var Resource $resource */
        $resource = $reflection->getAttributes(Resource::class)[0]->newInstance();
        $response->type = $resource->getType();

        foreach ($reflection->getProperties() as $property){
            $attributes = $property->getAttributes(ResourceDetail::class);
            if (!empty($attributes)){
                /** @var ResourceDetail $resourceDetail */
                $resourceDetail = $attributes[0]->newInstance();
                if (!array_key_exists('name', $attributes[0]->getArguments())){
                    $resourceDetail->setName($property->getName());
                }

                $value = null;

                if (array_key_exists('linkProperty', $attributes[0]->getArguments())){
                    $value = $reflection->getProperty($attributes[0]->getArguments()['linkProperty'])?->getValue();
                }

                /** @noinspection PhpExpressionResultUnusedInspection */
                $property->setAccessible(true);

                $value = ($value ?? '') . $property->getValue();

                self::setProperty(
                    resource: $response,
                    details: $resourceDetail,
                    value: $value,
                );
            }
        }

        return $response;
    }

    /**
     * @param ResourceObject $resource
     * @param ResourceDetail $details
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    private static function setProperty(
        ResourceObject $resource,
        ResourceDetail $details,
        mixed $value,
    ): void
    {
        if (self::$encrypter !== null && $details->isEncrypted()){
            $value = self::$encrypter->encryptId($value);
        }

        switch ($details->getType()){
            case ResourceDetailType::Id:
                $resource->id = $value;
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
                $resource->links->add(
                    new Link(
                        name: $details->getName(),
                        href: $value,
                    )
                );
                break;
            case ResourceDetailType::RelationshipLink:
                $resource->relationship($details->getName())->links->add(
                    new Link(
                        name: 'related',
                        href: $value,
                    )
                );
                break;
        }
    }
}