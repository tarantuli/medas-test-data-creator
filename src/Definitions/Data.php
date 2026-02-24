<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Definitions;

class Data
{
    public string $locale = 'nl_NL';

    /** @var Action[] */
    public array $actions = [];

    /** @var Definition[] */
    public array $definitions = [];
}
