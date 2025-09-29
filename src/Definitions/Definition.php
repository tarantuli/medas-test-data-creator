<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Definitions;

class Definition
{
    public string $entity;

    /** @var mixed[] */
    public array $properties = [];

    /** @var Action[] */
    public array $children = [];
}
