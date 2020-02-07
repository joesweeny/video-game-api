<?php

namespace App\Boundary\Game;

use App\Domain\Game\Game;
use App\Domain\Game\Persistence\Repository;

class GameService
{
    /**
     * @var Repository
     */
    private $gameRepository;
    /**
     * @var GamePresenter
     */
    private $presenter;

    public function __construct(Repository $gameRepository, GamePresenter $presenter)
    {
        $this->gameRepository = $gameRepository;
        $this->presenter = $presenter;
    }

    public function getGamesWithComments(): array
    {
        $games = $this->gameRepository->get();

        return array_map(function (Game $game) {
            return $this->presenter->toDto($game);
        }, $games);
    }
}
