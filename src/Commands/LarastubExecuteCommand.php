<?php

namespace Vassilidev\Larastub\Commands;

use Illuminate\Console\Command;
use Vassilidev\Larastub\Exceptions\TemplateFileMustImplementLarastubTemplateException;
use Vassilidev\Larastub\Exceptions\TemplateNameNotFoundException;
use Vassilidev\Larastub\Step;
use Vassilidev\Larastub\Template;
use Vassilidev\Stubbify\Exceptions\FileNotFoundException;
use Vassilidev\Stubbify\Exceptions\OutputFileAlreadyExistException;

class LarastubExecuteCommand extends Command
{
    public function __construct()
    {
        $this->signature = config('larastub.signature') . ' {templateName}';

        parent::__construct();
    }

    public $description = "Create file from template name";

    /**
     * @throws TemplateNameNotFoundException
     * @throws TemplateFileMustImplementLarastubTemplateException
     */
    public function handle(): int
    {
        $templateName = $this->argument('templateName');
        $allTemplatesNames = [];

        foreach (config('larastub.templates') as $class) {
            $allTemplatesNames[get_object_vars(new $class)['templateName']] = $class;
        }

        if (!array_key_exists($templateName, $allTemplatesNames)) {
            throw new TemplateNameNotFoundException($this->argument('templateName'));
        }

        $template = new $allTemplatesNames[$templateName];

        if (!is_a($template, Template::class)) {
            throw new TemplateFileMustImplementLarastubTemplateException($allTemplatesNames[$templateName]);
        }

        /** @var Template $template */
        /** @var Step $step */
        foreach ($template->steps() as $step) {
            if ($step->isOptional()) {
                $this->askOptionalStep($step);
            }

            try {
                if ($generator = $step->generate()) {
                    $this->info($generator->outputFilePath . ' successfully generated !');
                }
            } catch (FileNotFoundException|OutputFileAlreadyExistException $e) {
                $this->error($e->getMessage());

                continue;
            }
        }

        return self::SUCCESS;
    }

    private function askOptionalStep(Step $step): void
    {
        $shouldBeGenerated = $this->confirm(
            sprintf(
                'Do you want to generate the %s file ?',
                $step->getOutputFilePath(),
            )
        );

        $step->setShouldBeGenerated($shouldBeGenerated);
    }
}
