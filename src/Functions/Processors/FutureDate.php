<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;

#[Service]
readonly class FutureDate
{
    public function process(): string
    {
        $daysForward = mt_rand(1, 365);

        return (new \DateTime())->modify("+$daysForward day")->format('Y-m-d');
    }
}
