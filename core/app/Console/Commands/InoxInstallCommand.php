<?php

namespace App\Console\Commands;

use App\Core\Installer\Installer;
use Illuminate\Console\Command;

class InoxInstallCommand extends Command
{
    protected $signature = 'inox:install {--quick : Run with defaults, no prompts}';
    protected $description = 'Run the Inox installation wizard';

    public function handle(Installer $installer): int
    {
        if ($installer->isCompleted()) {
            $this->warn('Inox is already installed.');
            return Command::SUCCESS;
        }

        $quick = $this->option('quick');

        if ($quick) {
            $this->runAllSteps($installer, [
                'type' => 'website',
                'driver' => 'sqlite',
                'realtime' => false,
                'ai' => false,
                'name' => 'Admin',
                'email' => 'admin@inox.dev',
                'password' => 'password',
            ]);

            $this->info('Quick install completed.');
            return Command::SUCCESS;
        }

        $this->info('╔══════════════════════════════╗');
        $this->info('║     INOX Installation        ║');
        $this->info('╚══════════════════════════════╝');
        $this->newLine();

        $data = [
            'type' => $this->choice('Project type', ['website', 'ecommerce', 'api'], 'website'),
            'driver' => $this->choice('Database driver', ['sqlite', 'mysql'], 'sqlite'),
            'realtime' => $this->confirm('Enable real-time (WebSockets)?', false),
            'ai' => $this->confirm('Enable AI layer (Ollama, Claude, GPT)?', false),
            'name' => $this->ask('Admin name', 'Admin'),
            'email' => $this->ask('Admin email', 'admin@inox.dev'),
            'password' => $this->secret('Admin password'),
        ];

        $this->runAllSteps($installer, $data);

        $this->info('Installation completed successfully!');
        $this->warn('Run: php artisan inox:serve');

        return Command::SUCCESS;
    }

    protected function runAllSteps(Installer $installer, array $data): void
    {
        $installer->run(array_merge($data, ['step' => 'env']));
        $installer->run(array_merge($data, ['step' => 'type']));
        $installer->run(array_merge($data, ['step' => 'database']));
        $installer->run(array_merge($data, ['step' => 'features']));
        $installer->run(array_merge($data, ['step' => 'admin']));
        $installer->run(array_merge($data, ['step' => 'complete']));
    }
}
