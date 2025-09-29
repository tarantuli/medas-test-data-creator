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
}
