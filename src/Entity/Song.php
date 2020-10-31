<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Band::class, inversedBy="songs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $band;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $capo;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $song_name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $group_name;

    /**
     * @ORM\OneToMany(targetEntity=Riff::class, mappedBy="song", orphanRemoval=true)
     */
    private $riffs;

    public function __construct()
    {
        $this->riffs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBand(): ?Band
    {
        return $this->band;
    }

    public function setBand(?Band $band): self
    {
        $this->band = $band;

        return $this;
    }

    public function getCapo(): ?string
    {
        return $this->capo;
    }

    public function setCapo(string $capo): self
    {
        $this->capo = $capo;

        return $this;
    }

    public function getSongName(): ?string
    {
        return $this->song_name;
    }

    public function setSongName(string $song_name): self
    {
        $this->song_name = $song_name;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    /**
     * @return Collection|Riff[]
     */
    public function getRiffs(): Collection
    {
        return $this->riffs;
    }

    public function addRiff(Riff $riff): self
    {
        if (!$this->riffs->contains($riff)) {
            $this->riffs[] = $riff;
            $riff->setSong($this);
        }

        return $this;
    }

    public function removeRiff(Riff $riff): self
    {
        if ($this->riffs->contains($riff)) {
            $this->riffs->removeElement($riff);
            // set the owning side to null (unless already changed)
            if ($riff->getSong() === $this) {
                $riff->setSong(null);
            }
        }

        return $this;
    }

    // For BandType Validation

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('capo', new NotBlank());
        $metadata->addPropertyConstraint('song_name', new NotBlank());
        $metadata->addPropertyConstraint('group_name', new NotBlank());

        $metadata->addPropertyConstraint('capo', new Length([
            'min' => 1,
            'max' => 10,
            'minMessage' => 'Capo must be at least {{ limit }} characters long',
            'maxMessage' => 'Capo cannot be longer than {{ limit }} characters',
            'allowEmptyString' => false,
        ]));

        $metadata->addPropertyConstraint('song_name', new Length([
            'min' => 1,
            'max' => 30,
            'minMessage' => 'Song name must be at least {{ limit }} characters long',
            'maxMessage' => 'Song name cannot be longer than {{ limit }} characters',
            'allowEmptyString' => false,
        ]));

        $metadata->addPropertyConstraint('group_name', new Length([
            'min' => 1,
            'max' => 30,
            'minMessage' => 'Group name must be at least {{ limit }} characters long',
            'maxMessage' => 'Group name name cannot be longer than {{ limit }} characters',
            'allowEmptyString' => false,
        ]));
    }

    public function requireSongOf(Band $band)
    {
        if ($this->band != $band)
            throw new AccessDeniedException("Band does not contains this song!");
    }
}