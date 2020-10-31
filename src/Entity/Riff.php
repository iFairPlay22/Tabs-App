<?php

namespace App\Entity;

use App\Repository\RiffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RiffRepository::class)
 */
class Riff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Song::class, inversedBy="riffs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $song;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Chord::class, mappedBy="riff", orphanRemoval=true)
     */
    private $chords;

    public function __construct()
    {
        $this->chords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Chord[]
     */
    public function getChords(): Collection
    {
        $iterator = $this->chords->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getOrderBy() < $b->getOrderBy()) ? -1 : 1;
        });
        return new ArrayCollection(iterator_to_array($iterator));
        // return $this->chords;
    }

    public function addChord(Chord $chord): self
    {
        if (!$this->chords->contains($chord)) {
            $this->chords[] = $chord;
            $chord->setRiff($this);
        }

        return $this;
    }

    public function removeChord(Chord $chord): self
    {
        if ($this->chords->contains($chord)) {
            $this->chords->removeElement($chord);
            // set the owning side to null (unless already changed)
            if ($chord->getRiff() === $this) {
                $chord->setRiff(null);
            }
        }

        return $this;
    }
}
