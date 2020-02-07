<?php

namespace App\Providers;

use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\Comment\Persistence\DatabaseCommentRepository;
use App\Domain\Game\Persistence\DatabaseGameGameRepository;
use App\Domain\Game\Persistence\GameRepository;
use App\Domain\User\Persistence\DatabaseUserRepository;
use App\Domain\User\Persistence\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GameRepository::class, DatabaseGameGameRepository::class);
        $this->app->singleton(UserRepository::class, DatabaseUserRepository::class);
        $this->app->singleton(CommentRepository::class, DatabaseCommentRepository::class);
    }
}
