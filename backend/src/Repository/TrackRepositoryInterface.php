<?php 
namespace App\Repository;

use App\Entity\Track;

interface TrackRepositoryInterface
{
    public function findAll(): array;
    public function find(int $id): ?Track;
    public function save(Track $track): void;
}