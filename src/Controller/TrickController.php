<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\AddTrickFormType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Service\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    public function __construct(
        private TrickRepository $trickRepository,
        private ImageRepository $imageRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $entityManager
    ){
    }

    /**
     * Home page
     */
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        // Get tricks
        $tricks = [];
        $tricks = $this->trickRepository->findAllByDate();

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * Get details of a trick
     */
    #[Route('/trick/{slug}', name: 'get_trick', methods: ['GET'])]
    public function getTrick(string $slug): Response
    {
        // Find the Trick by its slug
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        if (empty($trick)) {
            $this->addFlash('danger', 'Cette figure n\'existe pas.');
            return $this->redirectToRoute('home');
        }
        // Get images and comments
        $images = $this->imageRepository->findAllByTrick(['trick_id' => $trick->getId()]);
        $comments = $this->commentRepository->findAllByTrick(['trick_id' => $trick->getId()]);

        return $this->render('tricks/get.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'comments' => $comments
        ]);
    }

    /**
     * Add a new trick
     */
    #[Route('/add_trick', name: 'add_trick', methods: ['GET', 'POST'])]
    public function addTrick(Request $request, ImageUpload $imageUploadService): Response
    {
        $trick = new Trick();
        $form = $this->createForm(AddTrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTrick = $form->getData();

            // save user logged in
            $newTrick->setUserID($this->getUser());

            // save new trick into db
            $this->entityManager->persist($newTrick);
            $this->entityManager->flush();

            // upload images
            $files = $form->get('images')->getData();
            if (!empty($files)) {
                foreach ($files as $file) {
                    $image = new Image;
                    $image->setImage($imageUploadService->uploadImage($file));
                    // get last trick and link with uploading image
                    $lastTrick = $this->trickRepository->findLastTrick();
                    $image->setTrickId($lastTrick);
                    // save image into db
                    $this->entityManager->persist($image);
                    $this->entityManager->flush();
                }
            }

            $this->addFlash('success', 'Un nouveau figure été ajouté.');

            return $this->redirectToRoute('home');
        }

        return $this->render('tricks/add.html.twig', [
            'addTrickForm' => $form->createView()
        ]);
    }

}