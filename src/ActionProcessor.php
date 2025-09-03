<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Medas\Core\Attributes\Service;

#[Service]
readonly class ActionProcessor
{
    public function __construct(
        private EntityClearer $clearer,
        private EntityCreator $creator,
    )
    {
    }

    public function process(Job $job, Definitions\Action $action): void
    {
        switch ($action->type) {
            case 'create':
                $this->creator->create($job, $action);

                break;

            case 'clear':
                $this->clearer->clear($job, $action);

                break;

            default:
                throw new Exceptions\UnknownActionType($action->type);
        }
    }
}
