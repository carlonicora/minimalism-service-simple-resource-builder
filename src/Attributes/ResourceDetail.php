<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes;

use Attribute;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceDetailType;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceValueTransformation;

#[Attribute]
class ResourceDetail
{
    /**
     * @param ResourceDetailType $type
     * @param ResourceValueTransformation|null $transformation
     * @param string|null $name
     * @param string|null $linkProperty
     * @param string|null $limitingProperty
     */
    public function __construct(
        private ResourceDetailType $type,
        private ?ResourceValueTransformation $transformation=null,
        private ?string $name=null,
        private ?string $linkProperty=null,
        private ?string $limitingProperty=null,
    )
    {
    }

    /**
     * @return ResourceDetailType
     */
    public function getType(
    ): ResourceDetailType
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getName(
    ): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(
        ?string $name,
    ): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getLinkProperty(
    ): ?string
    {
        return $this->linkProperty;
    }

    /**
     * @return string|null
     */
    public function getLimitingProperty(
    ): ?string
    {
        return $this->limitingProperty;
    }

    /**
     * @return ResourceValueTransformation|null
     */
    public function getTransformation(
    ): ?ResourceValueTransformation
    {
        return $this->transformation;
    }
}