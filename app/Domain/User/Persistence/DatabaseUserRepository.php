<?php

namespace App\Domain\User\Persistence;

use App\Domain\User\User;
use Illuminate\Database\Connection;
use Ramsey\Uuid\Uuid;

class DatabaseUserRepository implements UserRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(User $user): void
    {
        $row = new \DateTimeImmutable();

        $values = [
            'id' => $user->getId()->getBytes(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'created_at' => $user->getDateCreated()->getTimestamp(),
            'updated_at' => $user->getDateCreated()->getTimestamp(),
        ];

        $this->connection->table('user')->insert($values);
    }

    public function get(): array
    {
        $query = $this->connection->table('user');

        return array_map(function (\stdClass $row) {
            return $this->hydrateUserEntity($row);
        }, $query->get()->toArray());
    }

    private function hydrateUserEntity(\stdClass $row): User
    {
        $user = new User(
            Uuid::fromBytes($row->id),
            $row->name,
            $row->email
        );

        $user->setDateCreated(\DateTimeImmutable::createFromFormat('U', $row->created_at))
            ->setDateUpdated(\DateTimeImmutable::createFromFormat('U', $row->created_at));

        return $user;
    }
}
