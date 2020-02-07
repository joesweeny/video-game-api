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
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;

class GameServiceTest extends TestCase
{
    /**
     * @var GameRepository|ObjectProphecy
     */
    private $gameRepository;
    /**
     * @var CommentRepository|ObjectProphecy
     */
    private $commentRepository;
    /**
     * @var UserRepository|ObjectProphecy
     */
    private $userRepository;
    /**
     * @var GameService
     */
    private $service;

    public function setUp(): void
    {
        $this->gameRepository = $this->prophesize(GameRepository::class);
        $this->commentRepository = $this->prophesize(CommentRepository::class);
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->service = new GameService(
            $this->gameRepository->reveal(),
            new GamePresenter(),
            $this->commentRepository->reveal(),
            new CommentPresenter(),
            $this->userRepository->reveal()
        );
    }

    public function test_getGamesWithComments_returns_scalar_game_data_without_filtered_query()
    {
        $this->gameRepository->get(Argument::that(function (GameRepositoryQuery $query) {
            return $query->getNameEquals() === null;
        }))->willReturn([
               $game = new Game(
                    Uuid::fromString('e2ca7bb0-4ad6-413d-ac19-5b118be13003'),
                    'FIFA 20',
                    'Electronic Arts',
                    new \DateTimeImmutable('2020-02-07T00:00:00'),
                    'eagames_skeleton_key'
                )
            ]);

        $this->commentRepository->getByGameId($game->getId())->willReturn([
            $comment = new Comment(
                Uuid::fromString('c6daeb80-ca0a-4d75-8763-efe2f578a944'),
                Uuid::fromString('8e20fe71-e5db-4316-b788-4f14e41f58f9'),
                $game->getId(),
                'Pretty Awesome'
            )
        ]);

        $this->userRepository->getById($comment->getUserId())->willReturn(
            new User(Uuid::fromString('8e20fe71-e5db-4316-b788-4f14e41f58f9'), 'Joe', 'joe@email.com')
        );

        $games = $this->service->getGamesWithComments((object) []);

        $expected = [
            (object) [
                'id' => 'e2ca7bb0-4ad6-413d-ac19-5b118be13003',
                'name' => 'FIFA 20',
                'publisher' => 'Electronic Arts',
                'release_date' => '12am, 7th February 2020',
                'comments' => [
                    (object) [
                        'id' => 'c6daeb80-ca0a-4d75-8763-efe2f578a944',
                        'user' => 'Joe',
                        'text' => 'Pretty Awesome',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $games);
    }

    public function test_getGamesWithComments_returns_scalar_game_data_with_filtered_query()
    {
        $this->gameRepository->get(Argument::that(function (GameRepositoryQuery $query) {
            $this->assertEquals('Joe', $query->getNameEquals());
            $this->assertEquals('Electronic Arts', $query->getPublisherEquals());
            $this->assertEquals('2020-02-07', $query->getReleaseDateEquals());
            return true;
        }))->willReturn([
            $game = new Game(
                Uuid::fromString('e2ca7bb0-4ad6-413d-ac19-5b118be13003'),
                'FIFA 20',
                'Electronic Arts',
                new \DateTimeImmutable('2020-02-07T00:00:00'),
                'eagames_skeleton_key'
            )
        ]);

        $this->commentRepository->getByGameId($game->getId())->willReturn([
            $comment = new Comment(
                Uuid::fromString('c6daeb80-ca0a-4d75-8763-efe2f578a944'),
                Uuid::fromString('8e20fe71-e5db-4316-b788-4f14e41f58f9'),
                $game->getId(),
                'Pretty Awesome'
            )
        ]);

        $this->userRepository->getById($comment->getUserId())->willReturn(
            new User(Uuid::fromString('8e20fe71-e5db-4316-b788-4f14e41f58f9'), 'Joe', 'joe@email.com')
        );

        $query = (object) [
            'name' => 'Joe',
            'publisher' => 'Electronic Arts',
            'release_date' => '2020-02-07',
        ];

        $games = $this->service->getGamesWithComments($query);

        $expected = [
            (object) [
                'id' => 'e2ca7bb0-4ad6-413d-ac19-5b118be13003',
                'name' => 'FIFA 20',
                'publisher' => 'Electronic Arts',
                'release_date' => '12am, 7th February 2020',
                'comments' => [
                    (object) [
                        'id' => 'c6daeb80-ca0a-4d75-8763-efe2f578a944',
                        'user' => 'Joe',
                        'text' => 'Pretty Awesome',
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $games);
    }

    public function test_getGamesWithComments_throws_RuntimeException_if_unable_to_fetch_user_associated_to_a_comment()
    {
        $this->gameRepository->get(Argument::that(function (GameRepositoryQuery $query) {
            return $query->getNameEquals() === null;
        }))->willReturn([
            $game = new Game(
                Uuid::fromString('e2ca7bb0-4ad6-413d-ac19-5b118be13003'),
                'FIFA 20',
                'Electronic Arts',
                new \DateTimeImmutable('2020-02-07T00:00:00'),
                'eagames_skeleton_key'
            )
        ]);

        $this->commentRepository->getByGameId($game->getId())->willReturn([
            $comment = new Comment(
                Uuid::fromString('c6daeb80-ca0a-4d75-8763-efe2f578a944'),
                Uuid::fromString('8e20fe71-e5db-4316-b788-4f14e41f58f9'),
                $game->getId(),
                'Pretty Awesome'
            )
        ]);

        $this->userRepository->getById($comment->getUserId())->willThrow(new NotFoundException());

        $this->expectException(\RuntimeException::class);
        $this->service->getGamesWithComments((object) []);
    }
}
