<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Definitions;

class Action
{
    public string $type = 'create';
    public mixed $count;
    public string $entities = 'all';
    public string $definition;

    /** @var mixed[] */
    public array $properties = [];
}
