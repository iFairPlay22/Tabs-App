<?php

namespace App\Entity\Traits;

use DateTime;
use DateTimeInterface;
use Exception;

/**
 * Trait TimeStampableTrait
 * @package App\Entity\Trait
 */
trait TimeStampableTrait
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $updatedAt;

    /**
     * @return int|null
     * @throws Exception
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt == NULL ? NULL : strtotime($this->createdAt);
    }

    /**
     * @param int $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt->getTimestamp();

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt == NULL ? NULL : strtotime($this->updatedAt);
    }

    /**
     * @param int $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt->getTimestamp();

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateTimestamps(): void
    {
        $now = new DateTime();
        $this->setUpdatedAt($now);
        if ($this->getId() === null)
            $this->setCreatedAt($now);
    }
}
