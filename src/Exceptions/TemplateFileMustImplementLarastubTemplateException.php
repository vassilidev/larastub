<?php

namespace Vassilidev\Larastub\Exceptions;

use Exception;
use Vassilidev\Larastub\Template;

class TemplateFileMustImplementLarastubTemplateException extends Exception
{
    public function __construct(string $templateFile)
    {
        $errorMessage = sprintf('Template %s must implement %s !', $templateFile, Template::class);

        parent::__construct($errorMessage);
    }
}
