<?php

namespace App\Domain\Game\Persistence;

use App\Domain\Game\Game;

interface GameRepository
{
    public function insert(Game $game): void;

    /**
     * @param GameRepositoryQuery $query
     * @return array|Game[]
     */
    public function get(GameRepositoryQuery $query = null): array;
}
