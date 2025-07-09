<?php 
namespace App\Controller;

use App\DTO\TrackDTO;
use App\Service\TrackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//basic url ending
#[Route('/api/tracks')]

class TrackController extends AbstractController
{
    public function __construct(
        private TrackService $service,
        private ValidatorInterface $validator
    ) {}
    
    // Listing
    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $tracks = $this->service->list();

        $data = array_map(fn($track) => [
            'id' => $track->getId(),
            'title' => $track->getTitle(),
            'artist' => $track->getArtist(),
            'duration' => $track->getDuration(),
            'isrc' => $track->getIsrc(),
        ], $tracks);

        return $this->json($data);
    }

    //Creating
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = new TrackDTO();
    
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }
    
        $dto->title = $data['title'] ?? null;
        $dto->artist = $data['artist'] ?? null;
        $dto->duration = isset($data['duration']) ? (int)$data['duration'] : null;
        $dto->isrc = $data['isrc'] ?? null;
    
        //Validation before passing
        $errors = $this->validator->validate($dto);
    
        if (count($errors) > 0) {
            $validationErrors = [];
            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $validationErrors], 400);
        }
    
        try {
            $track = $this->service->create($dto);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    
        return $this->json([
            'id' => $track->getId(),
            'title' => $track->getTitle(),
            'artist' => $track->getArtist(),
            'duration' => $track->getDuration(),
            'isrc' => $track->getIsrc(),
        ], 201);
    }

    //Editing
    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $dto = new TrackDTO();

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $dto->title = $data['title'] ?? null;
        $dto->artist = $data['artist'] ?? null;
        $dto->duration = isset($data['duration']) ? (int)$data['duration'] : null;
        $dto->isrc = $data['isrc'] ?? null;

        //Validation before passing
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $validationErrors = [];
            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $validationErrors], 400);
        }

        $track = $this->service->update($id, $dto);

        if (!$track) {
            return $this->json(['error' => 'Track not found'], 404);
        }

        return $this->json([
            'id' => $track->getId(),
            'title' => $track->getTitle(),
            'artist' => $track->getArtist(),
            'duration' => $track->getDuration(),
            'isrc' => $track->getIsrc(),
        ]);
    }
}