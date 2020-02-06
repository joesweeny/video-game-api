<?php

namespace App\Boundary\User;

use App\Domain\User\User;

class UserPresenter
{
    public function toDto(User $user): \stdClass
    {
        return (object) [
            'id' => (string) $user->getId(),
            'name' => $user->getEmail()
        ];
    }
}
