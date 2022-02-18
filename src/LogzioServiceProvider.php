<?php

namespace RobMellett\Logzio;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use RobMellett\Logzio\Commands\LogzioCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-logzio_table')
            ->hasCommand(LogzioCommand::class);
    }
}
