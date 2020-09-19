<?php

namespace Derakht\RepositoryPattern\Console\Generators;

class RepositoryProviderGenerator extends Generator
{
    public $stub = 'Provider';
    public $endOfFile = '//{{END_OF_FILE}}';

    public function getRootNamespace()
    {
        return parent::getRootNamespace() . $this->getClassPath($this->getConfigClass());
    }

    public function getConfigClass()
    {
        return 'provider';
    }

    public function create()
    {
        if (!$this->filesystem->exists($path = $this->getPath())) {
            $this->filesystem->put($this->getPath(), $this->getStub());
        }
        $provider = $this->filesystem->get($this->getPath());
        $interface = '\\' . $this->getRepositoryInterface() . '::class';
        $eloquent = '\\' . $this->getRepositoryEloquentClass() . '::class';
        $this->filesystem->put(
            $this->getPath(),
            str_replace($this->endOfFile, PHP_EOL . "\$this->app->bind({$interface}, $eloquent);" . $this->endOfFile . PHP_EOL, $provider)
        );
    }

    public function getPath()
    {
        return app_path() . DIRECTORY_SEPARATOR . "Providers/{$this->getClassPath($this->getConfigClass(), true)}.php";
    }

    public function getRepositoryInterface()
    {
        $repositoryGenerator = new RepositoryInterfaceGenerator($this->name);
        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();
        return str_replace(["\\", '/'], '\\', $repository) . 'RepositoryInterface';
    }

    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getRepositoryEloquentClass()
    {
        $repositoryGenerator = new RepositoryEloquentGenerator($this->name);
        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();
        return str_replace(["\\", '/'], '\\', $repository) . 'Repository';
    }
}