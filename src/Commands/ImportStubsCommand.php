<?php

namespace Vassilidev\Larastub\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Vassilidev\Stubbify\Stubbify;

class ImportStubsCommand extends Command
{
    public $signature = 'larastub:import';

    public $description = 'Generate your stubs from a json !';

    private string $inputFilePath;

    /**
     * @throws \JsonException
     */
    public function handle(): int
    {
        $inputFilePath = base_path($this->ask("input file path (base path)"));

        if (!file_exists($inputFilePath)) {
            $this->error("Input file does not exist [$inputFilePath]");

            return self::INVALID;
        }

        if (!Str::endsWith($inputFilePath, '.json')) {
            $this->error('You have to pass a json file !');

            return self::INVALID;
        }

        $fileContent = file_get_contents($inputFilePath);

        foreach (json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR) ?? [] as $stub) {
            $this->generateFile(...$stub);
        }

        return self::SUCCESS;
    }

    private function generateFile($inputFilePath, $outputFilePath, array $data = [], bool $override = false): void
    {
        try {
            $success = Stubbify::make(
                inputFilePath : $inputFilePath,
                outputFilePath: $outputFilePath,
                data          : $data ?? [],
                override      : $override ?? false,
            );

            if ($success->success > 0) {
                $this->info("Successfully generated " . $outputFilePath);
            }
        }
        catch (Exception $e) {
            $this->error($e);
        }
    }
}

