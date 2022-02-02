<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Interfaces;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Interfaces\SimpleObjectInterface;

interface SimpleResourceObjectInterface extends SimpleObjectInterface
{
    /**
     * @return ResourceObject|null
     */
    public function buildResource(
    ): ?ResourceObject;

    /**
     * @param ResourceObject $data
     * @return void
     */
    public function ingestResource(
        ResourceObject $data,
    ): void;
}