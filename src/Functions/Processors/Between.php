<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\Exceptions\ParameterTooLargeToSquare;

#[Service]
readonly class Between
{
    public function process(array $parameters): int
    {
        $minValue = (int) $parameters[0];
        $maxValue = (int) $parameters[1];
        $distribution = $parameters[2] ?? null;

        if (in_array($distribution, ['low-bias', 'high-bias'], true)) {
            return $this->biasedDistributions($minValue, $maxValue, $distribution);
        }

        $min = max(min($minValue, PHP_INT_MAX), PHP_INT_MIN);
        $max = max(min($maxValue, PHP_INT_MAX), PHP_INT_MIN);

        return mt_rand($min, $max);
    }

    private function biasedDistributions(int $minValue, int $maxValue, string $distribution): int
    {
        $maxSafeValue = sqrt(PHP_INT_MAX);

        if ($minValue > $maxSafeValue) {
            throw new ParameterTooLargeToSquare($minValue);
        }

        if ($maxValue > $maxSafeValue) {
            throw new ParameterTooLargeToSquare($maxValue);
        }

        $minSquare = max(min($minValue * $minValue, PHP_INT_MAX), PHP_INT_MIN);
        $maxSquare = max(min($maxValue * $maxValue, PHP_INT_MAX), PHP_INT_MIN);

        switch ($distribution) {
            case 'low-bias':
                return ($minValue + $maxValue - (int) sqrt(mt_rand($minSquare, $maxSquare)));

            default:
            case 'high-bias':
                return (int) sqrt(mt_rand($minSquare, $maxSquare));
        }
    }
}
