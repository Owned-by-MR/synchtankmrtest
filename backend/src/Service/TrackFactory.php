<?php
namespace App\Service;

use App\DTO\TrackDTO;
use App\Entity\Track;

class TrackFactory
{
    public function createFromDTO(TrackDTO $dto): Track
    {
        $track = new Track();
        $track->setTitle($dto->title);
        $track->setArtist($dto->artist);
        $track->setDuration($dto->duration);
        $track->setIsrc($dto->isrc);
        return $track;
    }

    public function updateFromDTO(Track $track, TrackDTO $dto): void
    {
        $track->setTitle($dto->title);
        $track->setArtist($dto->artist);
        $track->setDuration($dto->duration);
        $track->setIsrc($dto->isrc);
    }
}
