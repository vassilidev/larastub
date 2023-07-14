<?php

namespace Vassilidev\Larastub;

abstract class Template
{
    public string $templateName;

    /**
     * Define all the step for the template.
     *
     * @return array
     */
    abstract public function steps(): array;

    public function step(
        string $inputPath,
        string $outputPath,
        array  $data,
        bool   $override = false,
        bool   $optional = false,
    ): Step
    {
        return new Step(
            inputFilePath: $inputPath,
            outputFilePath: $outputPath,
            data: $data,
            override: $override,
            optional: $optional,
        );
    }
}
