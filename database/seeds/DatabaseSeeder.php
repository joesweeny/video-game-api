<?php

use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\Game\Game;
use App\Domain\Game\Persistence\GameRepository;
use App\Domain\User\Persistence\UserRepository;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * @var GameRepository
     */
    private $gameRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(
        GameRepository $gameRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();
    }

    private function createData(): void
    {
        $games = [
            $game1 = new Game(
                Uuid::uuid4(),
                'Call of Duty: Modern Warfare',
                'Activision',
                new DateTimeImmutable('2019-10-25T12:00:00+00'),
                'cod.master_1234'
            ),
            $game2 = new Game(
                Uuid::uuid4(),
                'Code Vein',
                'Bandai Namco Entertainment',
                new DateTimeImmutable('2019-09-27T01:00:00+00'),
                'master_bandai_483'
            ),
            $game3 = new Game(
                Uuid::uuid4(),
                'Gears 5',
                'Xbox Game Studios',
                new DateTimeImmutable('2019-09-10T12:00:00+00'),
                'msoft_5gear_key_1'
            ),
            $game4 = new Game(
                Uuid::uuid4(),
                'Star Wars Jedi: Fallen Order',
                'Electronic Arts',
                new DateTimeImmutable('2019-11-15T02:00:00+00'),
                'eagames_skeleton_key'
            ),
            $game5 = new Game(
                Uuid::uuid4(),
                'FIFA 20',
                'Electronic Arts',
                new DateTimeImmutable('2019-09-27T03:00:00+00'),
                'eagames_skeleton_key'
            ),
        ];

        foreach ($games as $game) {
            $this->gameRepository->insert($game);
        }

        $users = [
            $user1 = new App\Domain\User\User(
                Uuid::uuid4(),
                'Dave Clark',
                'dave.clark@intelligencefusion.co.uk'
            ),
            $user2 = new App\Domain\User\User(
                Uuid::uuid4(),
                'Patricia Summer',
                'patricia.summer@intelligencefusion.co.uk'
            ),
            $user3 = new App\Domain\User\User(
                Uuid::uuid4(),
                'Thomas Jeffrey',
                't.j@intelligencefusion.co.uk'
            ),
        ];

        foreach ($users as $user) {
            $this->userRepository->insert($user);
        }

        $comments = [
            new App\Domain\Comment\Comment(
                Uuid::uuid4(),
                $user1->getId(),
                $game1->getId(),
                "Looks better than the original but isn't as good. Not worth the money."
            ),
            new App\Domain\Comment\Comment(
                Uuid::uuid4(),
                $user3->getId(),
                $game1->getId(),
                'Was hoping for something more.'
            ),
            new App\Domain\Comment\Comment(
                Uuid::uuid4(),
                $user2->getId(),
                $game4->getId(),
                "Amazing. Can't get enough of Star Wars, and this is the best installment yet."
            ),
        ];

        foreach ($comments as $comment) {
            $this->commentRepository->insert($comment);
        }
    }
}
