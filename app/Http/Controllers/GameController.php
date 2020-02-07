<?php

namespace App\Http\Controllers;

use App\Boundary\Game\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController
{
    /**
     * @var GameService
     */
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function list(Request $request): Response
    {
        $query = $this->buildQuery($request);

        $games = $this->gameService->getGamesWithComments($query);

        return new JsonResponse(['games' => $games]);
    }

    private function buildQuery(Request $request): \stdClass
    {
        $name = $request->get('name');
        $publisher = $request->get('publisher');
        $releaseDate = $request->get('release_date');

        $query = (object) [];

        if ($name !== null) {
            $query->name = $name;
        }

        if ($publisher !== null) {
            $query->publisher = $publisher;
        }

        if ($releaseDate !== null) {
            $query->release_date = $releaseDate;
        }

        return $query;
    }
}
