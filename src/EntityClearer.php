<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\{EntityClasses, EntityManager, Repository};

#[Service]
readonly class EntityClearer
{
    public function __construct(
        private EntityClasses $entityClasses,
        private EntityManager $entityManager,
        private Repository    $repository,
    )
    {
    }

    public function clear(Job $job, Definitions\Action $action): void
    {
        if ($action->entities === 'none') {
            return;
        }

        if ($action->entities === 'all') {
            if ($job->printProgress) {
                echo "Clearing all entities…\n";
            }

            foreach ($this->entityClasses->get() as $entity) {
                $this->clearEntity($entity);
            }

            if ($job->printProgress) {
                echo "   Clearing all entities: done\n";
            }

            return;
        }

        throw new Exceptions\UnknownEntitiesDefinition($action->entities);
    }

    private function clearEntity(string $className): void
    {
        foreach ($this->repository->fetchAll($className) as $entity) {
            $this->entityManager->delete($entity);
        }

        $this->entityManager->flush();
    }
}
