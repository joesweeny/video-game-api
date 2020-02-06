<?php

namespace App\Providers;

use App\Domain\Game\Persistence\DatabaseGameRepository;
use App\Domain\Game\Persistence\Repository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Repository::class, DatabaseGameRepository::class);
    }
}
