<?php

namespace Vassilidev\Larastub\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UninstallLarastubCommand extends Command
{
    public $signature = 'larastub:uninstall';

    public $description = "Remove all generated files from Larastub package";

    public function handle(): int
    {
        $filesystem = app(Filesystem::class);

        $configFile = config_path('larastub.php');

        if ($filesystem->exists($configFile)) {
            $filesystem->delete($configFile);
            $this->info('Successfully delete config file ! [' . $configFile . ']');
        }

        $this->info('If you have published the default stubs, you can delete them now');

        $this->newLine();
        $this->info('Successfully removed Larastub package !');

        return self::SUCCESS;
    }
}
