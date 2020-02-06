<?php

namespace App\Domain\Component;

trait Timestamps
{
    /**
     * @var \DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable|null
     */
    private $updatedAt;

    public function setDateCreated(\DateTimeImmutable $date): self
    {
        $this->createdAt = $date;
        return $this;
    }

    public function getDateCreated(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setDateUpdated(\DateTimeImmutable $date): self
    {
        $this->updatedAt = $date;
        return $this;
    }

    public function getDateUpdated(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
