<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class FilterParametersMustBeOdd extends BaseException
{
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }

    public function pattern(): string
    {
        return 'The number of filter parameters must be odd (entity name + pairs of properties and values), "%s" received';
    }
}
