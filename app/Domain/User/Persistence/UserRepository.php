<?php

namespace App\Domain\User\Persistence;

use App\Domain\Exception\NotFoundException;
use App\Domain\User\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function insert(User $user): void;

    /**
     * @return array|User[]
     */
    public function get(): array;

    /**
     * @param UuidInterface $id
     * @return User
     * @throws NotFoundException
     */
    public function getById(UuidInterface $id): User;
}
