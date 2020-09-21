<?php

namespace Derakht\RepositoryPattern;

use Illuminate\Support\ServiceProvider;
use Derakht\RepositoryPattern\Console\RepositoryPatternCommand;

class RepositoryPatternServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RepositoryPatternCommand::class,
            ]);
        }
        $this->publishes([
            __DIR__ . '/config/repository.php' => config_path('repository.php')
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/repository.php', 'repository');
        if (class_exists('\\App\\Providers\\RepositoryPatternServiceProvider')) {
            $this->app->register('\\App\\Providers\\RepositoryPatternServiceProvider');
        }
    }
}