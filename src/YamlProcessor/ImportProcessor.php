<?php

declare(strict_types=1);

namespace Medas\TestDataCreator\YamlProcessor;

use Medas\Core\{Attributes\Service, Exceptions\FailedToReadContent};
use Medas\TestDataCreator\{
    Exceptions\CircularImportDetected,
    Exceptions\ImportPathEscapesJobDirectory,
    Job
};

#[Service]
readonly class ImportProcessor
{
    public function process(Job $job, string $content): string
    {
        $imported = [];

        do {
            $foundImport = preg_match('/^(\s*)import\((.+?\.yaml)\)/m', $content, $match);

            if ($foundImport) {
                $filePath = realpath($job->directory . DIRECTORY_SEPARATOR . $match[2]);

                if ($filePath === false || !str_starts_with($filePath, realpath($job->directory))) {
                    throw new ImportPathEscapesJobDirectory($match[2], $job->directory);
                }

                if (in_array($filePath, $imported)) {
                    throw new CircularImportDetected($filePath);
                }

                $imported[] = $filePath;
                $newContent = file_get_contents($filePath);

                if ($newContent === false) {
                    throw new FailedToReadContent($filePath, 'file_get_contents() failed');
                }

                $newContent = preg_replace('/^/m', $match[1], $newContent);
                $content = str_replace($match[0], $newContent, $content);
            }
        } while ($foundImport);

        return $content;
    }
}
