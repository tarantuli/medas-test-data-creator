<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\Functions;

use Medas\Core\Attributes\Service;

#[Service]
readonly class Parser
{
    private const int READING_FUNCTION_NAME = 0;
    private const int READING_FUNCTION_PARAMETERS = 1;
    private const int DONE_READING_FUNCTION = 2;

    public function parse(string $value): Call|null
    {
        $length = strlen($value);
        $nextValueIsEscaped = false;
        $buffer = '';
        $parenthesesDepth = 0;
        $curlyBracesDepth = 0;
        $squareBracketsDepth = 0;
        $fase = self::READING_FUNCTION_NAME;
        $function = null;

        for ($p = 0; $p < $length; $p++) {
            $char = $value[$p];

            if ($nextValueIsEscaped) {
                $nextValueIsEscaped = false;
                $buffer .= $char;

                continue;
            }

            if ($char === '\\') {
                $nextValueIsEscaped = true;

                continue;
            }

            if ($fase === self::DONE_READING_FUNCTION) {
                return null;
            }

            if ($fase === self::READING_FUNCTION_PARAMETERS) {
                if ($char === '(') {
                    $parenthesesDepth++;

                    $buffer .= $char;

                    continue;
                }

                if ($char === ')') {
                    if ($parenthesesDepth === 0 && $curlyBracesDepth === 0 && $squareBracketsDepth === 0) {
                        $function->parameters[] = trim($buffer);
                        $buffer = '';
                        $fase = self::DONE_READING_FUNCTION;
                    }
                    else {
                        $parenthesesDepth--;

                        $buffer .= $char;
                    }

                    continue;
                }

                if ($char === '{') {
                    $curlyBracesDepth++;

                    $buffer .= $char;

                    continue;
                }

                if ($char === '}') {
                    $curlyBracesDepth--;

                    $buffer .= $char;

                    continue;
                }

                if ($char === '[') {
                    $squareBracketsDepth++;

                    $buffer .= $char;

                    continue;
                }

                if ($char === ']') {
                    $squareBracketsDepth--;

                    $buffer .= $char;

                    continue;
                }

                if ($char === ',' && $parenthesesDepth === 0 && $curlyBracesDepth === 0 && $squareBracketsDepth === 0) {
                    $function->parameters[] = trim($buffer);
                    $buffer = '';

                    continue;
                }
            }

            if ($fase === self::READING_FUNCTION_NAME && $char === '(') {
                $function = new Call($buffer);
                $buffer = '';
                $fase = self::READING_FUNCTION_PARAMETERS;

                continue;
            }

            if ($fase === self::READING_FUNCTION_NAME && !preg_match('/[a-zA-Z]/', $char)) {
                return null;
            }

            $buffer .= $char;
        }

        return $function;
    }
}
