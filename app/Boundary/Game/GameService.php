<?php

namespace App\Boundary\Game;

use App\Boundary\Comment\CommentPresenter;
use App\Domain\Comment\Comment;
use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\Exception\NotFoundException;
use App\Domain\Game\Game;
use App\Domain\Game\Persistence\GameRepository;
use App\Domain\Game\Persistence\GameRepositoryQuery;
use App\Domain\User\Persistence\UserRepository;
use Ramsey\Uuid\UuidInterface;

class GameService
{
    /**
     * @var GameRepository
     */
    private $gameRepository;
    /**
     * @var GamePresenter
     */
    private $presenter;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var CommentPresenter
     */
    private $commentPresenter;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        GameRepository $gameRepository,
        GamePresenter $presenter,
        CommentRepository $commentRepository,
        CommentPresenter $commentPresenter,
        UserRepository $userRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->presenter = $presenter;
        $this->commentRepository = $commentRepository;
        $this->commentPresenter = $commentPresenter;
        $this->userRepository = $userRepository;
    }

    public function getGamesWithComments(\stdClass $query): array
    {
        $query = $this->buildRepositoryQuery($query);

        $games = $this->gameRepository->get($query);

        return array_map(function (Game $game) {
            $dto = $this->presenter->toDto($game);

            $dto->comments = $this->getCommentsByGameId($game->getId());

            return $dto;
        }, $games);
    }

    private function getCommentsByGameId(UuidInterface $gameId): array
    {
        $comments = $this->commentRepository->getByGameId($gameId);

        return array_map(function (Comment $comment) {
            try {
                $user = $this->userRepository->getById($comment->getUserId());
            } catch (NotFoundException $e) {
                throw new \RuntimeException("Comment is not associated to a valid user");
            }

            return $this->commentPresenter->toDto($comment, $user);
        }, $comments);
    }

    private function buildRepositoryQuery(\stdClass $query): GameRepositoryQuery
    {
        $gpq = new GameRepositoryQuery();

        if (isset($query->name)) {
            $gpq->setNameEquals($query->name);
        }

        if (isset($query->publisher)) {
            $gpq->setPublisherEquals($query->publisher);
        }

        if (isset($query->release_date)) {
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', $query->release_date);

            if ($date === false) {
                throw new \InvalidArgumentException("Release date query parameter is not in the correct format");
            }
            $gpq->setReleaseDateEquals($date);
        }

        return $gpq;
    }
}
