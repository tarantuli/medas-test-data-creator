<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class UnknownFunctionName extends BaseException
{
    public function __construct(
        string $name,
    )
    {
        parent::__construct($name);
    }

    public function pattern(): string
    {
        return 'Unknown function name: %s';
    }
}
