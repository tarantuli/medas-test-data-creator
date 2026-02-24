<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;

#[Service]
readonly class SwitchProcessor
{
    public function process(array $parameters): string|null
    {
        $rand = mt_rand(1, 100);
        $cumulative = 0;

        foreach ($parameters as $parameter) {
            [$chance, $result] = explode(':', $parameter, 2);

            if ($rand <= ($cumulative += (int) $chance)) {
                return $result;
            }
        }

        return null;
    }
}
