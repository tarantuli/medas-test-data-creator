<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Faker\Factory;
use Medas\Core\{Attributes\Entrypoint, Attributes\Service, Exceptions\FailedToReadContent};
use Medas\EntityManager\EntityManager;
use Medas\ObjectToArraySerializer\ArrayToObjectCaster;
use Symfony\Component\Yaml\Yaml;

#[Service, Entrypoint]
readonly class YamlProcessor
{
    public function __construct(
        private ActionProcessor               $actionProcessor,
        private ArrayToObjectCaster           $arrayToObjectCaster,
        private EntityManager                 $entityManager,
        private YamlProcessor\ImportProcessor $importProcessor,
    )
    {
    }

    public function process(string $directory): void
    {
        $this->entityManager->autoPersistOnCreate(alsoFlush: false);

        $job = $this->createJob($directory);

        $this->processActions($job);

        if ($job->printProgress) {
            echo "Flushing…\n";
        }

        $this->entityManager->flush();

        if ($job->printProgress) {
            echo "   Flushing: done\n";
        }
    }

    private function processActions(Job $job): void
    {
        foreach ($job->data->actions as $action) {
            $this->actionProcessor->process($job, $action);
        }
    }

    public function createJob(string $directory): Job
    {
        $job = new Job($directory);
        $content = $this->getContent($job);

        $job->data = $this->arrayToObjectCaster->cast(
            Yaml::parse($content),
            Definitions\Data::class
        );

        $job->faker = Factory::create($job->data->locale);

        return $job;
    }

    private function getContent(Job $job): string
    {
        $file = $job->directory . DIRECTORY_SEPARATOR . 'index.yaml';
        $content = file_get_contents($file);

        if ($content === false) {
            throw new FailedToReadContent($file, 'file_get_contents() failed');
        }

        return $this->importProcessor->process($job, $content);
    }
}
