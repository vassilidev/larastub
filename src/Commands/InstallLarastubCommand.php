<?php

namespace Vassilidev\Larastub\Commands;

use Illuminate\Console\Command;

class InstallLarastubCommand extends Command
{
    public $signature = 'larastub:install';

    public $description = "Install Larastub package";

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'larastub-config'
        ]);

        if ($this->confirm('Should we publish the default stubs ?')) {
            $this->call('vendor:publish', [
                '--tag' => 'larastub-stubs'
            ]);
        }

        return self::SUCCESS;
    }
}
