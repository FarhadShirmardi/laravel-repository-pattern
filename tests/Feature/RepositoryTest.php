<?php

namespace Derakht\RepositoryPattern\Tests\Feature;

use Derakht\RepositoryPattern\Tests;

class RepositoryTest extends Tests\TestCase
{
    public function testGenerateRepository()
    {
        $this->artisan('make:repository', [
            'model' => 'Post',
            '--migration'
        ])
            ->expectsQuestion("A App\Post model does not exist. Do you want to generate it?", true)
            ->expectsQuestion("Do you want to generate migration?", true)
            ->expectsQuestion("Do you want to generate controller?", true)
            ->assertExitCode(0);
    }
}
