<?php

namespace Vassilidev\Larastub\Templates;

use Vassilidev\Larastub\Template;
use Vassilidev\Stubbify\Exceptions\FileNotFoundException;
use Vassilidev\Stubbify\Exceptions\OutputFileAlreadyExistException;

class HelloWorldTemplate extends Template
{
    public string $templateName = 'hello-world';

    /**
     * @throws OutputFileAlreadyExistException
     * @throws FileNotFoundException
     */
    public function steps(): array
    {
        return [
            $this->step(
                inputPath: resolveStubPath('dynamic.blade.php.stub'),
                outputPath: resource_path('views/larastub.blade.php'),
                data: [
                    '{{ time }}' => now()->toDateTimeString(),
                ],
            ),
            $this->step(
                inputPath: resolveStubPath('dynamic.blade.php.stub'),
                outputPath: resource_path('views/larastub1.blade.php'),
                data: [
                    '{{ time }}' => now()->toDateTimeString(),
                ],
                optional: 1
            ),
            $this->step(
                inputPath: resolveStubPath('dynamic.blade.php.stub'),
                outputPath: resource_path('views/larastub2.blade.php'),
                data: [
                    '{{ time }}' => now()->toDateTimeString(),
                ],
            ),
        ];
    }
}
