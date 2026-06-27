<?php

namespace App\Console\Commands;

use App\Core\ModuleEngine\ModuleEngine;
use App\Core\ThemeEngine\ThemeEngine;
use Illuminate\Console\Command;

class InoxCheckUpdatesCommand extends Command
{
    protected $signature = 'inox:check-updates
        {--type=all : Check updates for modules, themes, or all (default: all)}';

    protected $description = 'Check for module and theme updates from the marketplace';

    public function handle(ModuleEngine $modules, ThemeEngine $themes): int
    {
        $type = $this->option('type');

        if (in_array($type, ['all', 'modules'])) {
            $modules->discover();
            $registry = $modules->fetchRegistry();
            $updates = $modules->checkUpdates($registry);

            if (empty($updates)) {
                $this->info('All modules are up to date.');
            } else {
                $this->warn('Module updates available:');
                foreach ($updates as $name => $update) {
                    $this->line("  - {$name}: {$update['current_version']} → {$update['latest_version']}");
                }
            }
        }

        if (in_array($type, ['all', 'themes'])) {
            $themes->discover();
            $registry = $themes->fetchRegistry();
            $updates = $themes->checkUpdates($registry);

            if (empty($updates)) {
                $this->info('All themes are up to date.');
            } else {
                $this->warn('Theme updates available:');
                foreach ($updates as $name => $update) {
                    $this->line("  - {$name}: {$update['current_version']} → {$update['latest_version']}");
                }
            }
        }

        return Command::SUCCESS;
    }
}
