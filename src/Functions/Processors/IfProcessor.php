<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;

#[Service]
readonly class IfProcessor
{
    public function process(array $parameters): mixed
    {
        return $parameters[0] ? $parameters[1] : $parameters[2];
    }
}
