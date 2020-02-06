<?php

namespace App\Domain\Component\Persistence;

use App\Domain\Component\Comment;
use Illuminate\Database\Connection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DatabaseCommentRepository implements CommentRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Comment $comment): void
    {
        $now = new \DateTimeImmutable();

        $values = [
            'id' => $comment->getId(),
            'user_id' => $comment->getUserId()->getBytes(),
            'game_id' => $comment->getGameId()->getBytes(),
            'text' => $comment->getText(),
            'created_at' => $now->getTimestamp(),
        ];

        $this->connection->table('comment')->insert($values);
    }

    public function getByGameId(UuidInterface $gameId): array
    {
        $query = $this->connection->table('comment')->where('game_id', $gameId->getBytes());

        return array_map(function (\stdClass $row) {
            return $this->hydrateCommentEntity($row);
        }, $query->get()->toArray());
    }

    private function hydrateCommentEntity(\stdClass $row): Comment
    {
        $comment = new Comment(
            Uuid::fromBytes($row->id),
            Uuid::fromBytes($row->user_id),
            Uuid::fromBytes($row->game_id),
            $row->text
        );

        return $comment->setDateCreated(\DateTimeImmutable::createFromFormat('U', $row->created_at));
    }
}
