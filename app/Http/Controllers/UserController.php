<?php

namespace App\Http\Controllers;

use App\Boundary\User\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get(): Response
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse(['users' => $users]);
    }
}
