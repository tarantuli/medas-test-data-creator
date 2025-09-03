<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class UnknownActionType extends BaseException
{
    public function __construct(
        string $type,
    )
    {
        parent::__construct($type);
    }

    public function pattern(): string
    {
        return 'Unknown action type: %s';
    }
}
