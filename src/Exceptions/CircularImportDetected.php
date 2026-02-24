<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class CircularImportDetected extends BaseException
{
    public function __construct(string $filePath)
    {
        parent::__construct($filePath);
    }

    public function pattern(): string
    {
        return 'Circular import detected in "%s"';
    }
}
