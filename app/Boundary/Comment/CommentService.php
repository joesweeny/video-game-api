<?php

namespace App\Boundary\Comment;

use App\Domain\Comment\Comment;
use App\Domain\Comment\Persistence\CommentRepository;
use App\Domain\Exception\NotFoundException;
use App\Domain\User\Persistence\UserRepository;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class CommentService
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CommentPresenter
     */
    private $presenter;

    public function __construct(
        CommentRepository $commentRepository,
        UserRepository $userRepository,
        CommentPresenter $presenter
    ) {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
        $this->presenter = $presenter;
    }

    /**
     * @param string $userId
     * @return array|\stdClass[]
     * @throws InvalidUuidStringException
     * @throws NotFoundException
     */
    public function getByUserId(string $userId): array
    {
        $id = Uuid::fromString($userId);

        $user = $this->userRepository->getById($id);

        $comments = $this->commentRepository->getByUserId($id);

        return array_map(function (Comment $comment) use ($user) {
            return $this->presenter->toDto($comment, $user);
        }, $comments);
    }
}
