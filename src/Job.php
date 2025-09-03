<?php

declare(strict_types=1);

namespace Medas\TestDataCreator;

class Job
{
    public Definitions\Data $data;
    public bool $printProgress = true;

    public function __construct(
        public string $directory,
    )
    {
    }
}
