<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;
use Medas\TestDataCreator\Definitions\Action;

class NoDefinitionSpecifiedForCreateAction extends BaseException
{
    public function __construct(Action $action)
    {
        parent::__construct($action);
    }

    public function pattern(): string
    {
        return 'No definition specified for create action: %s';
    }
}
