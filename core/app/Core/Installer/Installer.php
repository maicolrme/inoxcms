<?php

namespace App\Core\Installer;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Installer
{
    protected array $steps = [
        'welcome' => 'Welcome',
        'type' => 'Project Type',
        'database' => 'Database Configuration',
        'features' => 'Optional Features',
        'admin' => 'Admin Account',
        'complete' => 'Complete',
    ];

    public function steps(): array
    {
        return $this->steps;
    }

    public function currentStep(): string
    {
        return config('inox.installer.step', 'welcome');
    }

    public function isCompleted(): bool
    {
        return (bool) config('inox.installer.completed', false);
    }

    public function run(array $data = []): bool
    {
        $step = $data['step'] ?? $this->currentStep();

        return match ($step) {
            'type' => $this->configureType($data),
            'database' => $this->configureDatabase($data),
            'features' => $this->configureFeatures($data),
            'admin' => $this->createAdmin($data),
            'complete' => $this->finalize(),
            default => false,
        };
    }

    protected function configureType(array $data): bool
    {
        $type = $data['type'] ?? 'website';

        $this->writeEnv('INOX_PROJECT_TYPE', $type);
        config(['inox.project_type' => $type]);

        $this->setStep('database');

        return true;
    }

    protected function configureDatabase(array $data): bool
    {
        $driver = $data['driver'] ?? 'sqlite';

        if ($driver === 'sqlite') {
            $path = database_path('database.sqlite');

            if (! File::exists($path)) {
                File::put($path, '');
            }

            $this->writeEnv('DB_CONNECTION', 'sqlite');
            $this->writeEnv('DB_DATABASE', $path);
        } else {
            $this->writeEnv('DB_CONNECTION', $driver);
            $this->writeEnv('DB_HOST', $data['host'] ?? '127.0.0.1');
            $this->writeEnv('DB_PORT', $data['port'] ?? '3306');
            $this->writeEnv('DB_DATABASE', $data['database'] ?? 'inox');
            $this->writeEnv('DB_USERNAME', $data['username'] ?? 'root');
            $this->writeEnv('DB_PASSWORD', $data['password'] ?? '');
        }

        Artisan::call('migrate', ['--force' => true]);

        $this->setStep('features');

        return true;
    }

    protected function configureFeatures(array $data): bool
    {
        $this->writeEnv('INOX_FEATURE_REALTIME', isset($data['realtime']) ? 'true' : 'false');
        $this->writeEnv('INOX_FEATURE_AI', isset($data['ai']) ? 'true' : 'false');

        $this->setStep('admin');

        return true;
    }

    protected function createAdmin(array $data): bool
    {
        $userClass = config('auth.providers.users.model');

        $userClass::updateOrCreate(
            ['email' => $data['email'] ?? 'admin@inox.dev'],
            [
                'name' => $data['name'] ?? 'Admin',
                'password' => Hash::make($data['password'] ?? Str::random(16)),
            ]
        );

        $this->setStep('complete');

        return true;
    }

    protected function finalize(): bool
    {
        $this->writeEnv('INOX_INSTALL_COMPLETED', 'true');
        $this->writeEnv('INOX_INSTALL_STEP', 'complete');

        config(['inox.installer.completed' => true]);
        config(['inox.installer.step' => 'complete']);

        return true;
    }

    protected function setStep(string $step): void
    {
        $this->writeEnv('INOX_INSTALL_STEP', $step);
        config(["inox.installer.step" => $step]);
    }

    protected function writeEnv(string $key, string $value): void
    {
        $envPath = cms_path('.env');

        if (! File::exists($envPath)) {
            return;
        }

        $env = File::get($envPath);

        if (str_contains($env, "$key=")) {
            $env = preg_replace("/^$key=.*/m", "$key=$value", $env);
        } else {
            $env .= "\n$key=$value";
        }

        File::put($envPath, $env);
    }
}
