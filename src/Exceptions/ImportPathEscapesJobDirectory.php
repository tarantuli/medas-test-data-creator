<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;

class ImportPathEscapesJobDirectory extends BaseException
{
    public function __construct(string $importPath, string $baseDirectory)
    {
        parent::__construct($importPath, $baseDirectory);
    }

    public function pattern(): string
    {
        return 'Import path "%s" escapes job directory "%s"';
    }
}
