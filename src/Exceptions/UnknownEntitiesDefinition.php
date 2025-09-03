<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class UnknownEntitiesDefinition extends BaseException
{
    public function __construct(
        string $definition,
    )
    {
        parent::__construct($definition);
    }

    public function pattern(): string
    {
        return 'Unknown entities definition: %s';
    }
}
