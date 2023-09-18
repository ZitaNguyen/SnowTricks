<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\AddTrickFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    private $trickRepository;
    private $entityManager;

    public function __construct(TrickRepository $trickRepository, EntityManagerInterface $entityManager)
    {
        $this->trickRepository = $trickRepository;
        $this->entityManager = $entityManager;
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

        return $this->render('tricks/get.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * Add a new trick
     */
    #[Route('/add_trick', name: 'add_trick', methods: ['GET', 'POST'])]
    public function addTrick(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(AddTrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTrick = $form->getData();

            // save user logged in
            $newTrick->setUserID($this->getUser());

            // save group
            // $selectedGroup = $form->get('group_id')->getData();
            // $newTrick->setGroupID($selectedGroup);

            $this->entityManager->persist($newTrick);
            $this->entityManager->flush();

            // Handle file uploads
            // $images = $form->get('images')->getData();

            // foreach ($images as $imageFile) {
            //     $image = new Image();
            //     $image->setImage($imageFile);
            //     $this->em->persist($image);
            //     $this->em->flush();
            // }

            $this->addFlash('success', 'Un nouveau figure été ajouté.');

            return $this->redirectToRoute('home');
        }

        return $this->render('tricks/add.html.twig', [
            'addTrickForm' => $form->createView()
        ]);
    }

}