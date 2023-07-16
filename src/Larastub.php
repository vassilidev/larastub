<?php

namespace Vassilidev\Larastub;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

class Larastub
{
    /**
     * @var Application
     */
    protected Application $app;

    public function __construct()
    {
        $this->app = app();
    }

    public function execute($templateName, $args): void
    {
        $args['templateName'] = $templateName;

        Artisan::call('larastub:execute', $args);
    }
}
