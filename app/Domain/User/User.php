<?php

namespace App\Domain\User;

use App\Domain\Component\Timestamps;
use Ramsey\Uuid\UuidInterface;

class User
{
    use Timestamps;

    /**
     * @var UuidInterface
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $email;

    public function __construct(UuidInterface $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
