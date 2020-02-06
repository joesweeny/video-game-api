<?php

namespace Domain\Game\Persistence;

use App\Domain\Game\Game;
use App\Domain\Game\Persistence\DatabaseGameRepository;
use App\Domain\Game\Persistence\GameRepositoryQuery;
use App\Domain\Game\Persistence\Repository;
use App\TestCase;
use Illuminate\Database\Connection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;

class DatabaseGameRepositoryIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Repository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->app->make(Connection::class);
        $this->repository = $this->app->make(Repository::class);
    }

    public function test_interface_is_bound()
    {
        $this->assertInstanceOf(DatabaseGameRepository::class, $this->repository);
    }

    public function test_insert_increases_table_count()
    {
        $game = new Game(
            Uuid::uuid4(),
            'Call of Duty: Modern Warfare',
            'Activision',
            new \DateTimeImmutable('2020-02-06T00:00:00'),
            'cod.master_1234'
        );

        $this->repository->insert($game);

        $count = $this->connection->table('game')->count();

        $this->assertEquals(1, $count);

        $this->repository->insert($game);

        $count = $this->connection->table('game')->count();

        $this->assertEquals(2, $count);
    }

    public function test_get_returns_all_records_if_no_query_argument_provided()
    {
        for ($i = 0; $i < 4; $i++) {
            $game = new Game(
                Uuid::uuid4(),
                'Call of Duty: Modern Warfare',
                'Activision',
                new \DateTimeImmutable('2020-02-06T00:00:00'),
                'cod.master_1234'
            );

            $this->repository->insert($game);
        }

        $total = $this->repository->get();

        $this->assertCount(4, $total);
    }

    public function test_get_filters_game_results_by_name()
    {
        for ($i = 0; $i < 4; $i++) {
            $game = new Game(
                Uuid::uuid4(),
                'Call of Duty: Modern Warfare',
                'Activision',
                new \DateTimeImmutable('2020-02-06T00:00:00'),
                'cod.master_1234'
            );

            $this->repository->insert($game);
        }

        $game = new Game(
            Uuid::uuid4(),
            'Code Vein',
            'Activision',
            new \DateTimeImmutable('2020-02-06T00:00:00'),
            'cod.master_1234'
        );

        $this->repository->insert($game);

        $total = $this->repository->get();
        $this->assertCount(5, $total);

        $query = (new GameRepositoryQuery())->setNameEquals('Code Vein');
        $total = $this->repository->get($query);
        $this->assertCount(1, $total);
    }

    public function test_get_filters_game_results_by_publisher()
    {
        for ($i = 0; $i < 4; $i++) {
            $game = new Game(
                Uuid::uuid4(),
                'Call of Duty: Modern Warfare',
                'Activision',
                new \DateTimeImmutable('2020-02-06T00:00:00'),
                'cod.master_1234'
            );

            $this->repository->insert($game);
        }

        $game = new Game(
            Uuid::uuid4(),
            'Code Vein',
            'Bandai Namco Entertainment',
            new \DateTimeImmutable('2020-02-06T00:00:00'),
            'cod.master_1234'
        );

        $this->repository->insert($game);

        $total = $this->repository->get();
        $this->assertCount(5, $total);

        $query = (new GameRepositoryQuery())->setPublisherEquals('Bandai Namco Entertainment');
        $total = $this->repository->get($query);
        $this->assertCount(1, $total);
    }

    public function test_get_filters_game_results_by_released_date()
    {
        for ($i = 0; $i < 4; $i++) {
            $game = new Game(
                Uuid::uuid4(),
                'Call of Duty: Modern Warfare',
                'Activision',
                new \DateTimeImmutable('2020-02-06T00:00:00'),
                'cod.master_1234'
            );

            $this->repository->insert($game);
        }

        $game = new Game(
            Uuid::uuid4(),
            'Code Vein',
            'Bandai Namco Entertainment',
            new \DateTimeImmutable('2020-02-08T00:00:00'),
            'cod.master_1234'
        );

        $this->repository->insert($game);

        $total = $this->repository->get();
        $this->assertCount(5, $total);

        $query = (new GameRepositoryQuery())->setPublisherEquals('Bandai Namco Entertainment');
        $total = $this->repository->get($query);
        $this->assertCount(1, $total);
    }
}
