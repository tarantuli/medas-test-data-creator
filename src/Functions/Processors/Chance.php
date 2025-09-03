<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;

#[Service]
readonly class Chance
{
    public function process(array $parameters): mixed
    {
        return mt_rand(1, 100) <= $parameters[0] ? $parameters[1] : ($parameters[2] ?? null);
    }
}
