<?php

namespace App\Boundary\Comment;

use App\Domain\Comment\Comment;

class CommentPresenter
{
    public function toDto(Comment $comment): \stdClass
    {
        return (object) [
            'id' => (string) $comment->getId(),
            'user_id' => (string) $comment->getUserId(),
            'game_id' => (string) $comment->getGameId(),
            'text' => $comment->getText(),
        ];
    }
}
