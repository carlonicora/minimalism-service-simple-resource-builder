<?php
namespace CarloNicora\Minimalism\Services\SimpleResourceBuilder;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Services\SimpleResourceBuilder\Factories\SimpleResourceFactory;

class SimpleResourceBuilder extends AbstractService
{
    /**
     * @param EncrypterInterface|null $encrypter
     */
    public function __construct(
        private ?EncrypterInterface $encrypter,
    )
    {
    }

    /**
     * @return void
     */
    public function initialise(
    ): void
    {
        SimpleResourceFactory::initialise($this->encrypter);
    }
}