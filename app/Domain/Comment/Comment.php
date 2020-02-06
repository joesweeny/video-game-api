<?php

namespace App\Domain\Component;

use Ramsey\Uuid\UuidInterface;

class Comment
{
    use Timestamps;

    /**
     * @var UuidInterface
     */
    private $id;
    /**
     * @var UuidInterface
     */
    private $userId;
    /**
     * @var UuidInterface
     */
    private $gameId;
    /**
     * @var string
     */
    private $text;

    public function __construct(
        UuidInterface $id,
        UuidInterface $userId,
        UuidInterface $gameId,
        string $text
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->gameId = $gameId;
        $this->text = $text;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getGameId(): UuidInterface
    {
        return $this->gameId;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
