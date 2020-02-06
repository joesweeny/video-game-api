<?php

namespace Domain\Comment\Persistence;

use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\Comment\Persistence\DatabaseCommentRepository;
use App\Domain\Comment\Comment;
use App\TestCase;
use Illuminate\Database\Connection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;

class DatabaseCommentRepositoryIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var CommentRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->app->make(Connection::class);
        $this->repository = $this->app->make(CommentRepository::class);
    }

    public function test_interface_is_bound()
    {
        $this->assertInstanceOf(DatabaseCommentRepository::class, $this->repository);
    }

    public function test_insert_increases_table_count()
    {
        $comment = new Comment(
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
            'Was hoping for something more'
        );

        $this->repository->insert($comment);

        $count = $this->connection->table('comment')->count();
        $this->assertEquals(1, $count);

        $this->repository->insert($comment);

        $count = $this->connection->table('comment')->count();
        $this->assertEquals(2, $count);
    }

    public function test_getByGameId_returns_comment_records_associated_to_a_game_id()
    {
        $gameId = Uuid::uuid4();

        for ($i = 0; $i < 4; $i++) {
            $comment = new Comment(
                Uuid::uuid4(),
                Uuid::uuid4(),
                $gameId,
                'Was hoping for something more'
            );

            $this->repository->insert($comment);
        }

        $comment = new Comment(
            Uuid::uuid4(),
            Uuid::uuid4(),
            Uuid::uuid4(),
            'Was hoping for something more'
        );

        $this->repository->insert($comment);

        $total = $this->connection->table('comment')->get();
        $this->assertCount(5, $total);

        $filtered = $this->repository->getByGameId($gameId);
        $this->assertCount(4, $filtered);

        foreach ($filtered as $comment) {
            $this->assertEquals($gameId, $comment->getGameId());
        }
    }

    public function test_getByUserId_returns_comment_records_associated_to_a_user_id()
    {
        $userId = Uuid::uuid4();

        for ($i = 0; $i < 4; $i++) {
            $comment = new Comment(
                Uuid::uuid4(),
                Uuid::uuid4(),
                Uuid::uuid4(),
                'Was hoping for something more'
            );

            $this->repository->insert($comment);
        }

        $comment = new Comment(
            Uuid::uuid4(),
            $userId,
            Uuid::uuid4(),
            'Was hoping for something more'
        );

        $this->repository->insert($comment);

        $total = $this->connection->table('comment')->get();
        $this->assertCount(5, $total);

        $filtered = $this->repository->getByUserId($userId);
        $this->assertCount(1, $filtered);

        foreach ($filtered as $comment) {
            $this->assertEquals($userId, $comment->getUserId());
        }
    }
}
