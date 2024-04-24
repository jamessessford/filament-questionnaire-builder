<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Commands;

use Illuminate\Console\Command;

class FilamentQuestionnaireBuilderCommand extends Command
{
    public $signature = 'filament-questionnaire-builder';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
