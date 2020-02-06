<?php

namespace Domain\Game\Persistence;

use App\Domain\Game\Persistence\DatabaseGameRepository;
use App\Domain\Game\Persistence\Repository;
use App\IntegrationTestCase;
use Illuminate\Database\Connection;

class DatabaseGameRepositoryIntegrationTest extends IntegrationTestCase
{
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
        $this->assertInstanceOf($this->repository, DatabaseGameRepository::class);
    }
}
