<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions;

use Faker\Factory;
use Faker\Generator;
use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\{Exceptions, ValueProcessor};

#[Service]
class Processor
{
    private int $uniqueInt = 0;
    private Generator $faker;

    public function __construct(
        private readonly Processors\Between         $betweenProcessor,
        private readonly Processors\Chance          $chanceProcessor,
        private readonly Processors\Filter          $filterProcessor,
        private readonly Processors\FutureDate      $futureDateProcessor,
        private readonly Processors\IfProcessor     $ifProcessor,
        private readonly Processors\SwitchProcessor $switchProcessor,
    )
    {
        $this->faker = Factory::create('nl_NL');
    }

    public function applyFunction(
        string         $function,
        array          $parameters,
        array          $context,
        ValueProcessor $valueProcessor
    ): mixed
    {
        array_walk(
            $parameters,
            fn(&$parameter) => $parameter = $valueProcessor->process($parameter, $context)
        );

        return match ($function) {
            'between' => $this->betweenProcessor->process($parameters),
            'chance' => $this->chanceProcessor->process($parameters),
            'email' => $this->faker->email(),
            'filter' => $this->filterProcessor->process($parameters),
            'futureDate' => $this->futureDateProcessor->process(),
            'if' => $this->ifProcessor->process($parameters),
            'name' => $this->faker->name(),
            'not' => !$valueProcessor->process($parameters[0], $context),
            'parent' => $context['parent'] ?? null,
            'password' => $this->faker->password(),
            'passwordHash' => password_hash($parameters[0], PASSWORD_DEFAULT),
            'random' => $this->faker->randomElement($parameters[0]),
            'switch' => $this->switchProcessor->process($parameters),
            'text' => '<p>' . implode('</p><p>', $this->faker->paragraphs(mt_rand(1, 5))) . '</p>',
            'title' => $this->faker->sentence(),
            'uniqueInt' => $this->uniqueInt++,
            default => throw new Exceptions\UnknownFunctionName($function),
        };
    }
}
