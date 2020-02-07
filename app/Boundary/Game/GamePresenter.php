<?php

namespace App\Boundary\Game;

use App\Domain\Game\Game;

class GamePresenter
{
    public function toDto(Game $game): \stdClass
    {
        return (object) [
            'id' => (string) $game->getId(),
            'name' => $game->getName(),
            'publisher' => $game->getPublisher(),
            'release_date' => $game->getReleased()->format(DATE_COOKIE)
        ];
    }
}
