<?php

namespace Derakht\RepositoryPattern\Console\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Exception;

abstract class Generator
{

    /**
     * @var Filesystem
     */
    protected $filesystem;
    protected $stub;
    protected $name;
    /**
     * @var array
     */
    private $options;

    public function __construct($name, $options = [])
    {
        $this->name = $name;
        $this->filesystem = new Filesystem();
        $this->options = $options;
    }

    public function getClassPath($class, $isDir = false)
    {
        if ($class == 'repository') {
            $path = config('repository.paths.repository', 'Repositories/Eloquent');
        } elseif ($class == 'repository_interface') {
            $path = config('repository.paths.repository_interface', 'Repositories/Contracts');
        } elseif ($class == 'provider') {
            $path = config('repository.paths.provider', 'RepositoryServiceProvider');
        } else {
            $path = '';
        }

        if ($isDir) {
            return str_replace('\\', '/', $path);
        } else {
            return str_replace('/', '\\', $path);
        }
    }

    /**
     * @return bool|int
     * @throws Exception
     */
    public function create()
    {
        if ($this->filesystem->exists($path = $this->getPath()) and !$this->force) {
            throw new Exception("$path already exists");
        }
        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }

        return $this->filesystem->put($path, $this->getStub());
    }

    abstract public function getPath();

    /**
     * @return false|string|string[]
     * @throws Exception
     */
    public function getStub()
    {
        if (!file_exists($path = __DIR__ . "/stubs/{$this->stub}.stub")) {
            throw new Exception("{$path} Does not exists.");
        }

        $content = file_get_contents($path);
        foreach ($this->getReplacements() as $key => $replacement) {
            $content = str_replace("{{{$key}}}", $replacement, $content);
        }

        return $content;
    }

    public function getReplacements()
    {
        return [
            'class' => $this->getClass(),
            'namespace' => $this->getNamespace()
        ];
    }

    public function getClass()
    {
        return Str::afterLast($this->getName(), '/');
    }

    public function getName()
    {
        $name = str_replace(['//', '\\', '/'], '/', $this->name);
        return Str::studly($name);
    }

    public function getNamespace()
    {
        $segments = explode('/', $this->getClass());
        array_pop($segments);
        $namespace = implode('\\', $segments);
        $namespace = rtrim("{$this->getRootNamespace()}\\{$namespace}", '\\');
        return "namespace {$namespace};";
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return $this->options[$name] ?? null;
    }


    public function getRootNamespace()
    {
        return 'App';
    }

    abstract public function getConfigClass();
}