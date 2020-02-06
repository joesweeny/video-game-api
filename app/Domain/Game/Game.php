<?php

namespace App\Domain\Game;

use App\Domain\Component\Timestamps;
use Ramsey\Uuid\Uuid;

class Game
{
    use Timestamps;

    /**
     * @var Uuid
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $publisher;
    /**
     * @var \DateTimeImmutable
     */
    private $released;
    /**
     * @var string
     */
    private $encryptionKey;

    public function __construct(
        Uuid $id,
        string $name,
        string $publisher,
        \DateTimeImmutable $released,
        string $encryptionKey
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->publisher = $publisher;
        $this->released = $released;
        $this->encryptionKey = $encryptionKey;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getReleased(): \DateTimeImmutable
    {
        return $this->released;
    }
    
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }
}
