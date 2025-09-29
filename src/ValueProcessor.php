<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Medas\Core\Attributes\Service;

/**
 * Faker documentation at https://fakerphp.org/formatters/
 */
#[Service]
readonly class ValueProcessor
{
    public function __construct(
        private Functions\Parser    $functionParser,
        private Functions\Processor $functionProcessor,
    )
    {
    }

    public function process(Job $job, mixed $value, array $context = []): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        if (preg_match('/^\d+$/', $value)) {
            return (int) $value;
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if ($value === 'null') {
            return null;
        }

        if ($function = $this->functionParser->parse($value)) {
            return $this->functionProcessor->applyFunction(
                $job,
                $function->name,
                $function->parameters,
                $context,
                $this,
            );
        }

        if (str_starts_with($value, '$')) {
            $name = substr($value, 1);

            if (!array_key_exists($name, $context)) {
                throw new \InvalidArgumentException("Context does not contain a value for '$name'");
            }

            $value = $context[$name];
        }

        return $value;
    }
}
