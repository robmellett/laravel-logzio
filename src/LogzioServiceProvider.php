<?php

namespace RobMellett\Logzio;

use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LogzioServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-logzio')
            ->hasConfigFile();
    }

    public function register()
    {
        parent::register();

        Log::extend('logzio', function ($app, array $config) {
            $handler = new Handler(
                $config['level'] ?? 'warning',
                $config['bubble'] ?? true,
                $config
            );

            return new Logger($config['name'] ?? $app->environment(), [$handler]);
        });
    }
}
