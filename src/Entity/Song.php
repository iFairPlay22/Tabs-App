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
use App\Entity\Traits\TimeStampableTrait;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`song`")
 */
class Song
{

    use TimeStampableTrait;

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
     * @ORM\Column(type="text")
     */
    private $guitar_tabs;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="songs")
     */
    private $tag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lyrics;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    // For BandType Validation

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('capo', new NotBlank());
        $metadata->addPropertyConstraint('song_name', new NotBlank());
        $metadata->addPropertyConstraint('group_name', new NotBlank());
        $metadata->addPropertyConstraint('guitar_tabs', new NotBlank());

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

        $metadata->addPropertyConstraint('guitar_tabs', new Length([
            'min' => 0,
            'max' => 10000,
            'minMessage' => 'Partition content must be at least {{ limit }} characters long',
            'maxMessage' => 'Partition content cannot be longer than {{ limit }} characters',
            'allowEmptyString' => true,
        ]));

        $metadata->addPropertyConstraint('lyrics', new Length([
            'min' => 0,
            'max' => 10000,
            'minMessage' => 'Lyrics content be at least {{ limit }} characters long',
            'maxMessage' => 'Lyrics content name cannot be longer than {{ limit }} characters',
            'allowEmptyString' => true,
        ]));
    }

    public function requireSongOf(Band $band)
    {
        if ($this->band != $band)
            throw new AccessDeniedException("Band does not contains this song!");
    }

    public function getGuitarTabs(): ?string
    {
        return $this->guitar_tabs;
    }

    public function setGuitarTabs(string $guitarTabs): self
    {
        $this->guitar_tabs = $guitarTabs;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->lyrics = $lyrics;

        return $this;
    }
}
