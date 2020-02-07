<?php

namespace App\Domain\Game\Persistence;

class GameRepositoryQuery
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $publisher;
    /**
     * @var \string|null
     */
    private $releasedDate;

    public function setNameEquals(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNameEquals(): ?string
    {
        return $this->name;
    }

    public function setPublisherEquals(string $publisher): self
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function getPublisherEquals(): ?string
    {
        return $this->publisher;
    }

    public function setReleaseDateEquals(string $date): self
    {
        $this->releasedDate = $date;
        return $this;
    }

    public function getReleaseDateEquals(): ?string
    {
        return $this->releasedDate;
    }
}
