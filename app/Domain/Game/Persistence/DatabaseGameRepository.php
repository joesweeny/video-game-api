<?php

namespace App\Domain\Game\Persistence;

use App\Domain\Game\Game;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Ramsey\Uuid\Uuid;

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
            'release_date' => $game->getReleased()->format('Y-m-d'),
            'encryption_key' => $game->getEncryptionKey(),
            'created_at' => $now->getTimestamp(),
            'updated_at' => $now->getTimestamp(),
        ];

        $this->connection->table('game')->insert($values);
    }

    public function get(GameRepositoryQuery $query = null): array
    {
        $query = $this->buildQuery($query);

        return array_map(function (\stdClass $row) {
            return $this->hydrateGameEntity($row);
        }, $query->get()->toArray());
    }

    private function buildQuery(GameRepositoryQuery $query = null): Builder
    {
        $builder = $this->connection->table('game');

        if ($query === null) {
            return $builder;
        }

        if ($query->getNameEquals() !== null) {
            $builder->where('name', $query->getNameEquals());
        }

        if ($query->getReleaseDateEquals() !== null) {
            $builder->where('release_date', $query->getReleaseDateEquals()->format('Y-m-d'));
        }

        if ($query->getPublisherEquals() !== null) {
            $builder->where('publisher', $query->getPublisherEquals());
        }

        return $builder;
    }

    private function hydrateGameEntity(\stdClass $row): Game
    {
        $game = new Game(
            Uuid::fromBytes($row->id),
            $row->name,
            $row->publisher,
            new \DateTimeImmutable($row->release_date),
            $row->encryption_key
        );

        $game->setDateCreated(\DateTimeImmutable::createFromFormat('U', $row->created_at))
            ->setDateUpdated(\DateTimeImmutable::createFromFormat('U', $row->created_at));

        return $game;
    }
}
