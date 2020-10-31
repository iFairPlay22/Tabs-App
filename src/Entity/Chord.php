<?php

namespace App\Entity;

use App\Repository\ChordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChordRepository::class)
 */
class Chord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Riff::class, inversedBy="chords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $riff;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_by;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $chord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRiff(): ?Riff
    {
        return $this->riff;
    }

    public function setRiff(?Riff $riff): self
    {
        $this->riff = $riff;

        return $this;
    }

    public function getOrderBy(): ?int
    {
        return $this->order_by;
    }

    public function setOrderBy(int $order_by): self
    {
        $this->order_by = $order_by;

        return $this;
    }

    public function getChord(): ?string
    {
        return $this->chord;
    }

    public function setChord(string $chord): self
    {
        $this->chord = $chord;
        return $this;
    }
}
