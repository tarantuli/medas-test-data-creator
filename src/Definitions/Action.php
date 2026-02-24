<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Definitions;

class Action
{
    public string $type = 'create';
    public mixed $count = null;
    public string $entities = 'all';
    public string|null $definition = null;

    /** @var mixed[] */
    public array $properties = [];
}
