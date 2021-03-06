<?php

namespace Domain\User\Persistence;

use App\Domain\Exception\NotFoundException;
use App\Domain\User\Persistence\DatabaseUserRepository;
use App\Domain\User\Persistence\UserRepository;
use App\Domain\User\User;
use App\TestCase;
use Illuminate\Database\Connection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;

class DatabaseUserRepositoryIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var UserRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->app->make(Connection::class);
        $this->repository = $this->app->make(UserRepository::class);
    }

    public function test_interface_is_bound()
    {
        $this->assertInstanceOf(DatabaseUserRepository::class, $this->repository);
    }

    public function test_insert_increases_table_count()
    {
        $user = new User(Uuid::uuid4(), 'Joe Sweeny', 'joe@email.com');
        $this->repository->insert($user);

        $count = $this->connection->table('user')->count();
        $this->assertEquals(1, $count);

        $user = new User(Uuid::uuid4(), 'Hulk Hogan', 'hulk@hogan.com');
        $this->repository->insert($user);

        $count = $this->connection->table('user')->count();
        $this->assertEquals(2, $count);
    }

    public function test_get_returns_all_records_from_the_database()
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User(Uuid::uuid4(), 'Joe Sweeny', 'joe@email.com');
            $this->repository->insert($user);
        }

        $total = $this->repository->get();
        $this->assertCount(5, $total);
    }

    public function test_getById_returns_a_user_object()
    {
        $id = Uuid::uuid4();

        $user = new User($id, 'Joe Sweeny', 'joe@email.com');
        $this->repository->insert($user);

        $fetched = $this->repository->getById($id);

        $this->assertEquals($id, $fetched->getId());
        $this->assertEquals('Joe Sweeny', $fetched->getName());
        $this->assertEquals('joe@email.com', $fetched->getEmail());
    }

    public function test_getById_throws_exception_if_user_does_not_exist()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->getById(Uuid::uuid4());
    }
}
