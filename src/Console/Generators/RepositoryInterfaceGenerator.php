<?php

namespace Derakht\RepositoryPattern\Console\Generators;

class RepositoryInterfaceGenerator extends Generator
{
    public $stub = 'RepositoryInterface';

    public function getRootNamespace()
    {
        return parent::getRootNamespace() . '\\' . $this->getClassPath($this->getConfigClass());
    }

    public function getConfigClass()
    {
        return 'repository_interface';
    }

    public function getPath()
    {
        return app_path() . DIRECTORY_SEPARATOR . $this->getClassPath($this->getConfigClass(), true) . DIRECTORY_SEPARATOR . "{$this->getName()}Repository.php";
    }
}