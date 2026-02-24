<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class InvalidIfParameters extends BaseException
{
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }

    public function pattern(): string
    {
        return '"if" requires exactly three parameters, "%s" received';
    }
}
