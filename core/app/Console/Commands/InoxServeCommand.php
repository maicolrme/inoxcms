<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InoxServeCommand extends Command
{
    protected $signature = 'inox:serve {--port=8080}';
    protected $description = 'Start the Inox development server';

    public function handle(): int
    {
        $port = $this->option('port');
        $host = 'localhost';

        $this->info("Inox development server starting on http://{$host}:{$port}");
        $this->info('Press Ctrl+C to stop.');
        $this->newLine();

        $process = new Process(['php', 'artisan', 'serve', "--port={$port}", "--host={$host}"]);
        $process->setTimeout(null);
        $process->setTty(true);
        $process->run();

        return Command::SUCCESS;
    }
}
