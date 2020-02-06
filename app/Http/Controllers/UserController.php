<?php

namespace App\Http\Controllers;

use App\Boundary\Comment\CommentService;
use App\Boundary\User\UserService;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var CommentService
     */
    private $commentService;

    public function __construct(UserService $userService, CommentService $commentService)
    {
        $this->userService = $userService;
        $this->commentService = $commentService;
    }

    public function list(): Response
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse(['users' => $users]);
    }

    public function comments(string $id): Response
    {
        try {
            $comments = $this->commentService->getByUserId($id);
        } catch (InvalidUuidStringException $e) {
            $body = [
                'error' => $e->getMessage(),
            ];

            return new JsonResponse($body, 422);
        }

        return new JsonResponse(['comments' => $comments]);
    }
}
