<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions\Processors;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\EntityManager;
use Medas\EntityManager\Repository;
use Medas\EntityManager\Selector\Selectors\WithValues;
use Medas\TestDataCreator\Exceptions\FilterParametersMustBeOdd;

#[Service]
readonly class Filter
{
    public function __construct(
        private EntityManager $entityManager,
        private Repository    $repository,
    )
    {
    }

    public function process(array $parameters): array
    {
        $entity = str_replace('/', '\\', $parameters[0]);
        $filters = [];
        $count = count($parameters);

        if ($count % 2 !== 1) {
            throw new FilterParametersMustBeOdd($parameters);
        }

        $filterCount = ($count - 1) / 2;

        for ($i = 0; $i < $filterCount; $i++) {
            $filters[$parameters[$i * 2 + 1]] = $parameters[$i * 2 + 2];
        }

        $this->entityManager->flush();

        return $this->repository->fetch(new WithValues($entity, $filters));
    }
}
