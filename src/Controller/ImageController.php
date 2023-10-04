<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ImageController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private EntityManagerInterface $entityManager
    ){
    }

    /**
     * Delete an image
     */
    #[Route('/delete_image/{imageID}', name: 'delete_image', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteImage(int $imageID)
    {
        try {
            // Find image
            $image = $this->imageRepository->findOneBy(['id' => $imageID]);
            // Delete image
            $this->entityManager->remove($image);
            $this->entityManager->flush();
            // Remove image file
            $imagePath = $this->getParameter('image_directory') . '/' . $image->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        } catch (\Exception $e) {
            $this->addFlash('warning', $e);
        }
        return new JsonResponse(['message' => 'Image deleted successfully']);
    }

}