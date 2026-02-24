<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

use Faker\Generator;

class Job
{
    public Definitions\Data $data;
    public bool $printProgress = true;
    public Generator $faker;

    public function __construct(
        public string $directory,
    )
    {
    }
}
