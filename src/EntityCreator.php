<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Medas\Core\Attributes\Service;
use Medas\EntityManager\EntityManager;

#[Service]
readonly class EntityCreator
{
    public function __construct(
        private EntityManager  $entityManager,
        private ValueProcessor $valueProcessor,
    )
    {
    }

    public function create(Job $job, Definitions\Action $action, array $context = []): void
    {
        if ($action->definition === null) {
            throw new Exceptions\NoDefinitionSpecifiedForCreateAction($action);
        }

        if ($action->count === null) {
            throw new Exceptions\NoCountSpecifiedForCreateAction($action);
        }

        $definition = clone $job->data->definitions[$action->definition];

        if ($definition->entity === null) {
            throw new Exceptions\NoEntitySpecifiedInDefinition($definition);
        }

        if ($action->properties) {
            $definition->properties = array_merge($definition->properties, $action->properties);
        }

        $count = $this->valueProcessor->process($job, $action->count, $context);

        if ($job->printProgress) {
            echo "Creating $count $definition->entity entities…\n";
        }

        for ($i = 0; $i < $count; $i++) {
            $this->createEntity($job, $definition, $context);
        }

        if ($job->printProgress) {
            echo "   Creating $count $definition->entity entities: done\n";
        }
    }

    public function createEntity(Job $job, Definitions\Definition $definition, array $context): object
    {
        $properties = [];

        foreach ($definition->properties as $name => $value) {
            $properties[$name] = $context[$name] = $this->valueProcessor->process(
                $job,
                $value,
                $context
            );
        }

        $entity = $this->entityManager->create($definition->entity, $properties);

        if ($definition->children) {
            foreach ($definition->children as $child) {
                $this->create($job, $child, ['parent' => $entity]);
            }
        }

        return $entity;
    }
}
