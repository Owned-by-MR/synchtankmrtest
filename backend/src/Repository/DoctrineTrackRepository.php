<?php 
namespace App\Repository;

use App\Entity\Track;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTrackRepository implements TrackRepositoryInterface
{
    public function __construct(private EntityManagerInterface $emi) {}

    public function findAll(): array
    {
        return $this->emi->getRepository(Track::class)->findAll();
    }

    public function find(int $id): ?Track
    {
        return $this->emi->getRepository(Track::class)->find($id);
    }

    public function save(Track $track): void
    {
        $this->emi->persist($track);
        $this->emi->flush();
    }
}