<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends AbstractController
{
    public function __construct(
        private VideoRepository $videoRepository,
        private EntityManagerInterface $entityManager
    ){
    }

    /**
     * Delete a video
     */
    #[Route('/delete_video/{videoID}', name: 'delete_video', methods: ['DELETE'])]
    public function deleteVideo(int $videoID)
    {
        try {
            // Find video
            $video = $this->videoRepository->findOneBy(['id' => $videoID]);
            // Delete video
            $this->entityManager->remove($video);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('warning', $e);
        }
        return new JsonResponse(['message' => 'Video deleted successfully']);
    }

}