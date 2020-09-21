# Laravel Repository Pattern Generator

**Generate repository structure with single command**

This package generates repository pattern files related to a model to use them in your app.

## Installation

Require this package with composer using the following command:

```bash
composer require derakht/repository-pattern
``` 

## Configuration
Publish the configuration file with th following command.
```bash
php artisan vendor:publish --provider="Derakht\RepositoryPattern\RepositoryPatternServiceProvider"
```
You can change default paths in this file.

## Usage
You can now run the below command to create repository files.
```bash
php artisan make:repository ModelName
``` 

## Example
```bash
php artisan make:repository Post
``` 
### PostController.php
```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Contract\PostRepositoryInterface;

class PostController extends Controller
{
    public $postRepository;

    public function __construct()
    {
        $this->postRepository = app(PostRepositoryInterface::class);
    }

    public function index()
    {
        return $this->postRepository->all();    
    }
}
```

or if you want to use injection add ```App\Providers\RepositoryServiceProvider::class``` to provider array in ```config/app.php```.

### PostController.php
```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Contract\PostRepositoryInterface;

class ReportController extends Controller
{
    public $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        return $this->postRepository->all();    
    }
}
```
