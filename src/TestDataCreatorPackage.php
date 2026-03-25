<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Medas\Core\{AsSingleton, BasePackage};
use Medas\EntityManager\EntityManagerPackage;

class TestDataCreatorPackage extends BasePackage
{
    use AsSingleton;

    public function dependencies(): array
    {
        return [
            EntityManagerPackage::instance(),
        ];
    }

    public function sourceDirectory(): string
    {
        return __DIR__;
    }
}
