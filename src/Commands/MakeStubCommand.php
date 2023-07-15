<?php

namespace Vassilidev\Larastub\Commands;

use Exception;
use Illuminate\Console\Command;
use Vassilidev\Stubbify\Stubbify;

class MakeStubCommand extends Command
{

    public $signature = 'larastub:make-stub';

    public $description = 'Generate stub file from existing file';
    private mixed $tempAskInFile;
    private mixed $tempReplaceInFile;
    private string $inputFilePath;
    private mixed $outputFilePath;
    private bool $override;
    private array $dataArray = [];
    private string|false $fileContent;

    public function handle(): int
    {
        $continueLooping = true;

        do {
            $this->inputFilePath = base_path($this->ask("input file path (base path)"));

            if (!file_exists($this->inputFilePath)) {
                $this->error("Input file does not exist [$this->inputFilePath]");
                
                continue;
            }

            $this->fileContent = file_get_contents($this->inputFilePath);

            $this->dataArray = [];

            do {
                $this->tempAskInFile = $this->ask(
                    "what you want to replace (if empty, will generate the output file)"
                );

                if (is_null($this->tempAskInFile)) {
                    break;
                }

                $this->tempReplaceInFile = $this->ask('what should it be replaced with?');

                $this->summaryChange();

                if ($this->confirm("is this good?")) {
                    $this->pushChanges();
                }
            } while (true);

            $this->summaryAllChanges();

            $this->outputFilePath = $this->ask("output file path?");

            $this->checkOutputFileAlreadyExists();
            $this->generateFile();

            $continueLooping = $this->confirm("continue stubbing");
        } while ($continueLooping);

        return self::SUCCESS;
    }

    private function summaryChange(): void
    {
        $this->info("You're going to find and replace");
        $this->info($this->tempAskInFile . " -> " . $this->tempReplaceInFile);
    }

    private function pushChanges(): void
    {

        $this->dataArray[] = [
            "find"    => $this->tempAskInFile,
            "replace" => $this->tempReplaceInFile,
            "amount"  => substr_count(haystack: $this->fileContent, needle: $this->tempAskInFile)
        ];
    }

    private function summaryAllChanges(): void
    {
        $this->info("Table of changes");

        $this->table(
            ["find", "replace", "amount"],
            $this->dataArray
        );
    }

    private function checkOutputFileAlreadyExists()
    {
        $this->override = false;

        if (file_exists($this->outputFilePath)) {
            $this->alert("Output file already exists");
            $this->override = $this->confirm("Do you want to override?");
        }
    }

    private function generateFile(): void
    {
        try {
            $success = Stubbify::make(
                inputFilePath : $this->inputFilePath,
                outputFilePath: $this->outputFilePath,
                data          : array_column($this->dataArray, "replace", "find"),
                override      : $this->override
            );

            if ($success->success > 0) {
                $this->info("Successfully generated " . $this->outputFilePath);
            }
        }
        catch (Exception $e) {
            $this->error($e);
        }
    }
}

