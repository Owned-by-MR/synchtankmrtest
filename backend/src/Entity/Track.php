<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'track')]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $artist;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $duration;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[A-Z]{2}-[A-Z0-9]{3}-\d{2}-\d{5}$/',
        message: 'ISRC format must be XX-XXX-00-00000'
    )]
    private ?string $isrc = null;
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }
    public function getArtist(): string { return $this->artist; }
    public function setArtist(string $artist): self { $this->artist = $artist; return $this; }
    public function getDuration(): int { return $this->duration; }
    public function setDuration(int $duration): self { $this->duration = $duration; return $this; }
    public function getIsrc(): ?string { return $this->isrc; }
    public function setIsrc(?string $isrc): self { $this->isrc = $isrc; return $this; }
}