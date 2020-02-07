<?php

namespace App\Domain\User\Persistence;

use App\Domain\Exception\NotFoundException;
use App\Domain\User\User;
use Illuminate\Database\Connection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
        $now = new \DateTimeImmutable();

        $values = [
            'id' => $user->getId()->getBytes(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'created_at' => $now->getTimestamp(),
            'updated_at' => $now->getTimestamp(),
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

    public function getById(UuidInterface $id): User
    {
        $row = $this->connection->table('user')->where('id', $id->getBytes())->first();

        if ($row === null) {
            throw new NotFoundException("User with {$id} does not exist");
        }

        return $this->hydrateUserEntity($row);
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
