<?php

namespace App\Boundary\User;

use App\Domain\User\Persistence\UserRepository;
use App\Domain\User\User;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPresenter
     */
    private $presenter;

    public function __construct(UserRepository $userRepository, UserPresenter $presenter)
    {
        $this->userRepository = $userRepository;
        $this->presenter = $presenter;
    }

    /**
     * @return array|\stdClass[]
     */
    public function getAllUsers(): array
    {
        $users = $this->userRepository->get();

        return array_map(function (User $user) {
            return $this->presenter->toDto($user);
        }, $users);
    }
}
