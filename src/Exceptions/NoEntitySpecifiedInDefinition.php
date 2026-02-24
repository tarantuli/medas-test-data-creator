<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;
use Medas\TestDataCreator\Definitions\Definition;

class NoEntitySpecifiedInDefinition extends BaseException
{
    public function __construct(Definition $definition)
    {
        parent::__construct($definition);
    }

    public function pattern(): string
    {
        return 'No entity specified in definition: %s';
    }
}
