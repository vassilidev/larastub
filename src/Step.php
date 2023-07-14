<?php

namespace Vassilidev\Larastub;

use Vassilidev\Stubbify\Exceptions\FileNotFoundException;
use Vassilidev\Stubbify\Exceptions\OutputFileAlreadyExistException;
use Vassilidev\Stubbify\Generator;

class Step
{
    private bool $shouldBeGenerated;

    public function __construct(
        private readonly string $inputFilePath,
        private readonly string $outputFilePath,
        private readonly array  $data = [],
        private readonly bool   $override = false,
        private readonly bool   $optional = false,
    )
    {
        $this->shouldBeGenerated = !$this->optional;
    }

    /**
     * @return bool|Generator It returns false if the file shouldn't be generated or the generator.
     * @throws FileNotFoundException
     *
     * @throws OutputFileAlreadyExistException
     */
    public function generate(): bool|Generator
    {
        if (!$this->shouldBeGenerated()) {
            return false;
        }

        return (new Generator(
            inputFilePath: $this->getInputFilePath(),
            outputFilePath: $this->getOutputFilePath(),
            data: $this->getData(),
            override: $this->isOverride(),
        ))->generate();
    }

    /**
     * @return string
     */
    public function getInputFilePath(): string
    {
        return $this->inputFilePath;
    }

    /**
     * @return string
     */
    public function getOutputFilePath(): string
    {
        return $this->outputFilePath;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isOverride(): bool
    {
        return $this->override;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @return bool
     */
    public function shouldBeGenerated(): bool
    {
        return $this->shouldBeGenerated;
    }

    /**
     * @param bool $shouldBeGenerated
     */
    public function setShouldBeGenerated(bool $shouldBeGenerated): void
    {
        $this->shouldBeGenerated = $shouldBeGenerated;
    }
}
