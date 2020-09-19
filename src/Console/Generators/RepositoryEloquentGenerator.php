<?php

namespace Derakht\RepositoryPattern\Console\Generators;

class RepositoryEloquentGenerator extends Generator
{
    public $stub = 'Repository';

    public function getRootNamespace()
    {
        return parent::getRootNamespace() . '\\' . $this->getClassPath($this->getConfigClass());
    }

    public function getConfigClass()
    {
        return 'repository';
    }

    public function getPath()
    {
        return app_path() . DIRECTORY_SEPARATOR . $this->getClassPath($this->getConfigClass(), true) . DIRECTORY_SEPARATOR . "{$this->getName()}Repository.php";
    }

    public function getReplacements()
    {
        $interface = parent::getRootNamespace() . '\\' . parent::getClassPath('repository_interface') . '\\' . "{$this->name}RepositoryInterface";

        return array_merge(parent::getReplacements(), [
            'interface' => $interface
        ]);
    }
}