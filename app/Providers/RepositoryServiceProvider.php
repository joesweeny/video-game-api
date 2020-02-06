<?php

namespace App\Providers;

use App\Domain\Component\Persistence\CommentRepository;
use App\Domain\Component\Persistence\DatabaseCommentRepository;
use App\Domain\Game\Persistence\DatabaseGameRepository;
use App\Domain\Game\Persistence\Repository;
use App\Domain\User\Persistence\DatabaseUserRepository;
use App\Domain\User\Persistence\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Repository::class, DatabaseGameRepository::class);
        $this->app->singleton(UserRepository::class, DatabaseUserRepository::class);
        $this->app->singleton(CommentRepository::class, DatabaseCommentRepository::class);
    }
}
