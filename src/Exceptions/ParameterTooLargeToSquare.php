<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class ParameterTooLargeToSquare extends BaseException
{
    public function __construct(mixed $parameter)
    {
        parent::__construct($parameter);
    }

    public function pattern(): string
    {
        return 'parameter "%s" too large to square';
    }
}
