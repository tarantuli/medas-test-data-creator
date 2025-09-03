<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\YamlProcessor;

use Medas\Core\Attributes\Service;
use Medas\TestDataCreator\Job;

#[Service]
readonly class ImportProcessor
{
    public function process(Job $job, string $content): string
    {
        do {
            $foundImport = preg_match('/^(\s*)import\((.+?\.yaml)\)/m', $content, $match);

            if ($foundImport) {
                $newContent = file_get_contents($job->directory . DIRECTORY_SEPARATOR . $match[2]);
                $newContent = preg_replace('/^/m', $match[1], $newContent);
                $content = str_replace($match[0], $newContent, $content);
            }
        } while ($foundImport);

        return $content;
    }
}
