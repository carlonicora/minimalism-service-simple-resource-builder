<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder\Traits;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Factories\SimpleResourceFactory;
use Exception;

trait SimpleResourceObjectTrait
{
    /**
     * @return ResourceObject
     * @throws Exception
     */
    public function buildResource(
    ): ResourceObject
    {
        return SimpleResourceFactory::buildResource($this);
    }

    /**
     * @param ResourceObject $data
     * @return void
     */
    public function ingestResource(
        ResourceObject $data,
    ): void
    {

    }
}