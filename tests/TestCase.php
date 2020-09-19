<?php

namespace Derakht\RepositoryPattern\Tests;

use Orchestra\Testbench\TestCase as TestCaseAlias;
use Derakht\RepositoryPattern\RepositoryPatternServiceProvider;

abstract class TestCase extends TestCaseAlias
{
    protected function getPackageProviders($app)
    {
        return [
            RepositoryPatternServiceProvider::class
        ];
    }
}
