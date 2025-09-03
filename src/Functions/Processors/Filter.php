<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\Repository;
use Medas\EntityManager\Selector\Selectors\WithValues;

#[Service]
readonly class Filter
{
    public function __construct(
        private Repository $repository,
    )
    {
    }

    public function process(array $parameters): array
    {
        $entity = str_replace('/', '\\', $parameters[0]);
        $filters = [];
        $filterCount = (count($parameters) - 1) / 2;

        for ($i = 0; $i < $filterCount; $i++) {
            $filters[$parameters[$i * 2 + 1]] = $parameters[$i * 2 + 2];
        }

        em()->flush();

        return $this->repository->fetch(new WithValues($entity, $filters));
    }
}
