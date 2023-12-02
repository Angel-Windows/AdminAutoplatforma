<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';

    protected $description = 'Clear and rewrite log files';

    public function handle()
    {
        $logFiles = File::glob(storage_path('logs/*.log'));

        foreach ($logFiles as $logFile) {
            File::put($logFile, '');
        }

        $this->info('Log files cleared and rewritten.');
    }
}
