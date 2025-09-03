<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;

#[Service]
readonly class Between
{
    public function process(array $parameters): int
    {
        return match ($parameters[2] ?? null) {
            'low-bias' => ($parameters[0] + $parameters[1] - (int) sqrt(mt_rand(
                $parameters[0] * $parameters[0],
                $parameters[1] * $parameters[1]
            ))),

            'high-bias' => (int) sqrt(mt_rand($parameters[0] * $parameters[0], $parameters[1] * $parameters[1])),
            default => mt_rand($parameters[0], $parameters[1]),
        };
    }
}
