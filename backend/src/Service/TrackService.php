<?php
namespace App\Service;

use App\DTO\TrackDTO;
use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;

//All logic for creating and updating tracks is handled in the TrackService
class TrackService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TrackFactory $factory
    ) {}

    public function list(): array
    {
        return $this->entityManager->getRepository(Track::class)->findAll();
    }

    public function create(TrackDTO $dto): Track
    {
        $track = $this->factory->createFromDTO($dto);
        $this->entityManager->persist($track);
        $this->entityManager->flush();
        return $track;
    }

    public function update(int $id, TrackDTO $dto): ?Track
    {
        $track = $this->entityManager->getRepository(Track::class)->find($id);
        if (!$track) {
            return null;
        }

        $this->factory->updateFromDTO($track, $dto);
        $this->entityManager->flush();

        return $track;
    }
}
