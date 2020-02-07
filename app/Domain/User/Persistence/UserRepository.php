<?php

namespace App\Domain\User\Persistence;

use App\Domain\User\User;

interface UserRepository
{
    public function insert(User $user): void;

    /**
     * @return array|User[]
     */
    public function get(): array;

    
}
