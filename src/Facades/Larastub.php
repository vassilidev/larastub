<?php

namespace Vassilidev\Larastub\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void execute(string $templateName, ...$args)
 */
class Larastub extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return \Vassilidev\Larastub\Larastub::class;
    }
}
