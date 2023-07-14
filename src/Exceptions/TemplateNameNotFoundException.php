<?php

namespace Vassilidev\Larastub\Exceptions;

use Exception;

class TemplateNameNotFoundException extends Exception
{
    public function __construct(string $missingTemplateName)
    {
        $errorMessage = sprintf(
            'Template name named "%s" not found ! Check your larastub.php config file !',
            $missingTemplateName
        );

        parent::__construct($errorMessage);
    }
}
