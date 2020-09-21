<?php

namespace Derakht\RepositoryPattern\Tests\Feature;

use Derakht\RepositoryPattern\Tests;

class RepositoryTest extends Tests\TestCase
{
    public function testGenerateRepository()
    {
        $this->artisan('vendor:publish --tag="config"');
        $this->artisan('make:repository', [
            'model' => 'Post',
            '--force' => true
        ])
            ->expectsQuestion("A App\Post model does not exist. Do you want to generate it?", true)
            ->expectsQuestion("Do you want to generate migration?", true)
            ->expectsQuestion("Do you want to generate controller?", true)
            ->assertExitCode(0);
    }
}
