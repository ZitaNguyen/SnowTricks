<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\AddTrickFormType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    private $trickRepository;
    private $em;

    public function __construct(TrickRepository $trickRepository, EntityManagerInterface $em)
    {
        $this->trickRepository = $trickRepository;
        $this->em = $em;
    }

    /**
     *
     */
    public function index(): Response
    {
        // Get tricks
        $tricks = $this->trickRepository->getTricks();

        if (empty($tricks)) {
            throw $this->createNotFoundException('No trick added');
        }

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     *
     */
    public function getTrick(string $slug): Response
    {
        // Find the Trick by its slug
        $trick = $this->trickRepository->findTrickBySlug($slug);

        if (empty($trick)) {
            throw $this->createNotFoundException('No post found for this slug');
        }

        return $this->render('tricks/get.html.twig', [
            'trick' => $trick[0]
        ]);
    }

    /**
     *
     */
    public function addTrick(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(AddTrickFormType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newTrick = $form->getData();
            // $newTrick->setUserId($this->getUser()->getId());
            // $newTrick->setGroupId('1');
            $this->em->persist($newTrick);
            $this->em->flush();

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