<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\{Definitions\Definition, EntityCreator, Job};

#[Service]
readonly class Create
{
    public function process(Job $job, array $parameters): object
    {
        $defition = new Definition();

        $defition->entity = $parameters[0];

        return \service(EntityCreator::class)->createEntity($job, $defition, []);
    }
}
