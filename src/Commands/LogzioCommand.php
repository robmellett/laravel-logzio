<?php

namespace RobMellett\Logzio\Commands;

use Illuminate\Console\Command;

class LogzioCommand extends Command
{
    public $signature = 'laravel-logzio';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
