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
    }

    public function register()
    {
        if (class_exists('\\App\\Providers\\RepositoryPatternServiceProvider')) {
            $this->app->register('\\App\\Providers\\RepositoryPatternServiceProvider');
        }
    }
}