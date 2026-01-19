<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\{EntityCreator, Job};

#[Service]
readonly class Create
{
    public function process(Job $job, array $parameters): object
    {
        $definition = clone $job->data->definitions[$parameters[0]];

        return \service(EntityCreator::class)->createEntity($job, $definition, []);
    }

    public function processArray(Job $job, array $parameters): array
    {
        $array = [];
        $definition = clone $job->data->definitions[$parameters[0]];
        $count = $parameters[1];

        for ($i = 0; $i < $count; $i++) {
            $array[] = \service(EntityCreator::class)->createEntity($job, $definition, []);
        }

        return $array;
    }
}
