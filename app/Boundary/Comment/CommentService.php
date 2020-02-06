<?php

namespace App\Boundary\Comment;

use App\Domain\Comment\Comment;
use App\Domain\Comment\Persistence\CommentRepository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class CommentService
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var CommentPresenter
     */
    private $presenter;

    public function __construct(CommentRepository $commentRepository, CommentPresenter $presenter)
    {
        $this->commentRepository = $commentRepository;
        $this->presenter = $presenter;
    }

    /**
     * @param string $userId
     * @return array|\stdClass[]
     * @throws InvalidUuidStringException
     */
    public function getByUserId(string $userId): array
    {
        $id = Uuid::fromString($userId);

        $comments = $this->commentRepository->getByUserId($id);

        return array_map(function (Comment $comment) {
            return $this->presenter->toDto($comment);
        }, $comments);
    }
}
