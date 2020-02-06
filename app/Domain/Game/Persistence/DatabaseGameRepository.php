<?php

namespace App\Domain\Game\Persistence;

use App\Domain\Game\Game;
use Illuminate\Database\Connection;

class DatabaseGameRepository implements Repository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Game $game): void
    {
        $now = new \DateTimeImmutable();

        $values = [
            'id' => $game->getId()->getBytes(),
            'name' => $game->getName(),
            'publisher' => $game->getPublisher(),
            'release_date' => $game->getReleased()->getTimestamp(),
            'encryption_key' => $game->getEncryptionKey(),
            'created_at' => $now->getTimestamp(),
            'updated_at' => $now->getTimestamp(),
        ];

        $this->connection->table('game')->insert($values);
    }
    
    public function get(GameRepositoryQuery $query): array
    {
        return [];
    }
}
