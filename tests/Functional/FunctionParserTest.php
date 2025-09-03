<?php

declare(strict_types=1);

namespace Functional;

use Medas\TestDataCreator\Functions\{Call, Parser};
use PHPUnit\Framework\TestCase;

class FunctionParserTest extends TestCase
{
    private array $testValues;

    public function __construct()
    {
        $this->testValues = [
            'passwordHash(kaas)' => new Call('passwordHash', ['kaas']),
            'not(true)' => new Call('not', ['true']),
            'between(10,50)' => new Call('between', ['10', '50']),
            'between(10, 50)' => new Call('between', ['10', '50']),
            'chance(10, 0, between(1, 50, low-bias))' => new Call(
                'chance',
                ['10', '0', 'between(1, 50, low-bias)']
            ),
            'random(filter(&items,[isHeader=true,owner=parent()]))' => new Call(
                'random',
                ['filter(&items,[isHeader=true,owner=parent()])']
            ),
        ];

        parent::__construct();
    }

    public function testValues(): void
    {
        $parser = service(Parser::class);

        foreach ($this->testValues as $value => $expected) {
            $result = $parser->parse($value);

            if (json_encode($result) !== json_encode($expected)) {
                printf("Value: %s\n", $value);
                printf("Expected: %s\n", json_encode($expected, JSON_PRETTY_PRINT));
                printf("Result: %s\n", json_encode($result, JSON_PRETTY_PRINT));
            }

            $this->assertEquals($expected, $result);
        }
    }
}
