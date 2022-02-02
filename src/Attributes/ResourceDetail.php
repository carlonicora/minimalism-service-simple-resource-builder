<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes;

use Attribute;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Enums\ResourceDetailType;

#[Attribute]
class ResourceDetail
{
    /**
     * @param ResourceDetailType $type
     * @param bool $isEncrypted
     * @param string|null $name
     * @param string|null $linkProperty
     */
    public function __construct(
        private ResourceDetailType $type,
        private bool               $isEncrypted=false,
        private ?string            $name=null,
        private ?string            $linkProperty=null,
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
     * @return bool
     */
    public function isEncrypted(
    ): bool
    {
        return $this->isEncrypted;
    }
}