<?php

namespace App\Boundary\Comment;

use App\Domain\Comment\Comment;
use App\Domain\User\User;

class CommentPresenter
{
    public function toDto(Comment $comment, User $user): \stdClass
    {
        return (object) [
            'id' => (string) $comment->getId(),
            'name' => (string) $user->getName(),
            'text' => $comment->getText(),
        ];
    }
}
