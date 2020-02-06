<?php

namespace App\Domain\Component\Persistence;

use App\Domain\Component\Comment;
use Ramsey\Uuid\UuidInterface;

interface CommentRepository
{
    public function insert(Comment $comment): void;

    /**
     * @param UuidInterface $gameId
     * @return array|Comment[]
     */
    public function getByGameId(UuidInterface $gameId): array;
}
