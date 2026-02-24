<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\Exceptions\InvalidIfParameters;

#[Service]
readonly class IfProcessor
{
    public function process(array $parameters): mixed
    {
        if (!array_key_exists(0, $parameters)
                || !array_key_exists(1, $parameters)
                || !array_key_exists(2, $parameters)) {
            throw new InvalidIfParameters($parameters);
        }

        return $parameters[0] ? $parameters[1] : $parameters[2];
    }
}
