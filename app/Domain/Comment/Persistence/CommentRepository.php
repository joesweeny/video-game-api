<?php

namespace App\Domain\Comment\Persistence;

use App\Domain\Comment\Comment;
use Ramsey\Uuid\UuidInterface;

interface CommentRepository
{
    public function insert(Comment $comment): void;

    /**
     * @param UuidInterface $gameId
     * @return array|Comment[]
     */
    public function getByGameId(UuidInterface $gameId): array;

    /**
     * @param UuidInterface $userId
     * @return array|Comment[]
     */
    public function getByUserId(UuidInterface $userId): array;
}
