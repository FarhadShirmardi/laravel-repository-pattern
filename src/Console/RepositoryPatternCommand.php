<?php

namespace Derakht\RepositoryPattern\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Derakht\RepositoryPattern\Console\Generators\RepositoryEloquentGenerator;
use Derakht\RepositoryPattern\Console\Generators\RepositoryInterfaceGenerator;
use Derakht\RepositoryPattern\Console\Generators\RepositoryProviderGenerator;
use Exception;

class RepositoryPatternCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model : Model name e.g User, Post} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('model');
        $options = [
            'force' => $this->hasOption('force')
        ];
        try {
            $this->buildModel($model);
            (new RepositoryEloquentGenerator($model, $options))->create();
            (new RepositoryInterfaceGenerator($model, $options))->create();
            (new RepositoryProviderGenerator($model, $options))->create();
            $this->info('Repository Created Successfully!');
        } catch (Exception $exception) {
            $this->error($exception->getTraceAsString());
        }
    }

    private function buildModel($model)
    {
        $model = Str::studly($model);
        $modelClass = $this->parseModel($model);

        if (!class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $arguments = [
                    'name' => $model
                ];
                if ($this->confirm("Do you want to generate migration?", true)) {
                    array_push($arguments, ['--migration']);
                }
                if ($this->confirm("Do you want to generate controller?", true)) {
                    array_push($arguments, ['--controller']);
                }
                $this->call('make:model', $arguments);
            }
        }
    }

    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }

    protected function getOptions()
    {
        return [
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
        ];
    }
}