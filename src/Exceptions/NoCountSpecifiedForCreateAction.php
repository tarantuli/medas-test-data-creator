<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Exceptions;

use Medas\Core\Exceptions\BaseException;
use Medas\TestDataCreator\Definitions\Action;

class NoCountSpecifiedForCreateAction extends BaseException
{
    public function __construct(Action $action)
    {
        parent::__construct($action);
    }

    public function pattern(): string
    {
        return 'No count specified for create action: %s';
    }
}
