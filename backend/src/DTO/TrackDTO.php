<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TrackDTO
{
    #[Assert\NotBlank]
    public ?string $title = null;

    #[Assert\NotBlank]
    public ?string $artist = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public ?int $duration = null;

    #[Assert\Regex(
        pattern: '/^[A-Z]{2}-[A-Z0-9]{3}-\d{2}-\d{5}$/',
        message: 'ISRC format must be XX-XXX-00-00000'
    )]
    public ?string $isrc = null;
}
