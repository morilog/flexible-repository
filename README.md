# Laravel Flexible Repository
A Laravel package for creating Flexible and powerful repositories

## Installation
```shell
composer require morilog/flexible-repository
```

## Usage

### Create Repositories

#### Create for Eloquent
- Create an `interface` for your repository that extended from `Morilog\FlexibleRepository\Contracts\RepositoryInterface`

```php
<?php
namesapce App\Reposiotries;

use Morilog\FlexibleRepository\Contracts\RepositoryInterface;

interface UserRepository extends RepositoryInterface
{
}
```


- Create a class For your model that extended from `Morilog\FlexibleRepository\BaseEloquentRepository` and implements your declared `interface`

```php
<?php
namespace App\Repositories;

use Morilog\FlexibleRepository\BaseEloquentRepository;
use App\Models\User;

class EloquentUserRepository extends BaseEloquentRepository implements UserRepository
{
    protected function model()
    {
        return User::class;
    }
}
```


- Bind your Repository to implemented class

```php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\EloqeuntUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
         $this->app->bind(UserRepository::class, function ($app) {
            return new EloquentUserRepository($app);
         });
         
        // or
        // $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        
    }
}
```

- Inject your repository in classes and controller methods
```php
<?php
namespace App\Http\Controllers;

use App\Repositories\UserReposiotry;

class UsersController extends Controller
{
    public function index(UserRepository $repository)
    {
        retrun $repository->all();
    }
}

```
