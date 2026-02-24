<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions;

class Call
{
    public function __construct(
        public string $name,

        /** @var string[] */
        public array  $parameters = [],
    )
    {
    }
}
