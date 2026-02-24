<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\{Exceptions, Job, ValueProcessor};

#[Service]
class Processor
{
    private int $uniqueInt = 0;

    public function __construct(
        private readonly Processors\Between         $betweenProcessor,
        private readonly Processors\Chance          $chanceProcessor,
        private readonly Processors\Create          $createProcessor,
        private readonly Processors\Filter          $filterProcessor,
        private readonly Processors\FutureDate      $futureDateProcessor,
        private readonly Processors\IfProcessor     $ifProcessor,
        private readonly Processors\SwitchProcessor $switchProcessor,
    )
    {
    }

    public function applyFunction(
        Job            $job,
        string         $function,
        array          $parameters,
        array          $context,
        ValueProcessor $valueProcessor
    ): mixed
    {
        array_walk(
            $parameters,
            fn(&$parameter) => $parameter = $valueProcessor->process($job, $parameter, $context)
        );

        return match ($function) {
            'between' => $this->betweenProcessor->process($parameters),
            'chance' => $this->chanceProcessor->process($parameters),
            'create' => $this->createProcessor->process($job, $parameters),
            'createArray' => $this->createProcessor->processArray($job, $parameters),
            'email' => $job->faker->email(),
            'filter' => $this->filterProcessor->process($parameters),
            'futureDate' => $this->futureDateProcessor->process(),
            'if' => $this->ifProcessor->process($parameters),
            'name' => $job->faker->name(),
            'not' => !$parameters[0],
            'parent' => $context['parent'] ?? null,
            'password' => $job->faker->password(),
            'passwordHash' => password_hash($parameters[0], PASSWORD_DEFAULT),
            'question' => rtrim($job->faker->sentence(20), '. ') . '?',
            'random' => $job->faker->randomElement($parameters[0]),
            'switch' => $this->switchProcessor->process($parameters),
            'text' => '<p>'
                . implode('</p><p>', $job->faker->paragraphs($job->faker->numberBetween(1, 5)))
                . '</p>',

            'title' => rtrim($job->faker->sentence(), '. '),
            'uniqueInt' => $this->uniqueInt++,
            'word' => rtrim($job->faker->word(), '. '),
            default => throw new Exceptions\UnknownFunctionName($function),
        };
    }
}
