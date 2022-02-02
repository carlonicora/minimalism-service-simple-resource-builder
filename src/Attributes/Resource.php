<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Attributes;

use Attribute;

#[Attribute]
class Resource
{
    /**
     * @param string $type
     */
    public function __construct(
        private string $type,
    )
    {
    }

    /**
     * @return string
     */
    public function getType(
    ): string
    {
        return $this->type;
    }
}