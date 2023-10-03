<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Entity\Video;
use App\Form\AddTrickFormType;
use App\Form\ModifyTrickFormType;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\GroupRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Service\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class TrickController extends AbstractController
{
    public function __construct(
        private TrickRepository $trickRepository,
        private GroupRepository $groupRepository,
        private ImageRepository $imageRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $entityManager
    ){
    }

    /**
     * Home page
     */
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Get tricks
        $tricks = $this->trickRepository->findAllByDate($request->query->getInt('page', 1));

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * Tricks page
     */
    #[Route('/tricks', name: 'all_tricks', methods: ['GET'])]
    public function getTricks(Request $request): Response
    {
        // Get tricks
        $tricks = $this->trickRepository->findAllByDate($request->query->getInt('page', 1));

        return $this->render('tricks/getAll.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * Get details of a trick
     */
    #[Route('/trick/{slug}', name: 'get_trick', methods: ['GET','POST'])]
    public function getTrick(string $slug, Request $request, PaginatorInterface $paginator): Response
    {
        // Find the Trick by its slug
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        if (empty($trick)) {
            $this->addFlash('danger', 'Cette figure n\'existe pas.');
            return $this->redirectToRoute('home');
        }
        // Get images, videos and comments
        $images = $trick->getImages();
        $videos = $trick->getVideos();
        $comments = $this->entityManager->getRepository(Comment::class)->findBy(
            ['trick' => $trick],
            ['createdAt' => 'DESC']
        );
        $comments = $paginator->paginate($comments, $request->query->getInt('page', 1), 10);

        // Comment form
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            // set user
            $comment->setUser($this->getUser());
            // set trick
            $comment->setTrick($trick);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('get_trick', ['slug' => $slug]);
        }

        return $this->render('tricks/get.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos,
            'comments' => $comments,
            'commentForm' => $commentForm
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
            $newTrick->setUser($this->getUser());

            // save new trick into db
            $this->entityManager->persist($newTrick);
            $this->entityManager->flush();

             // get recently added trick
             $lastTrick = $this->trickRepository->findLastTrick();

            // upload images
            $files = $form->get('images')->getData();
            if (!empty($files)) {
                foreach ($files as $file) {
                    $image = new Image;
                    $image->setImage($imageUploadService->uploadImage($file));
                    // link last trick with uploading image
                    $image->setTrick($lastTrick);
                    // save image into db
                    $this->entityManager->persist($image);
                    $this->entityManager->flush();
                }
            }

            // upload videos
            $urls = $form->get('videos')->getData();
            if (!empty($urls)) {
                foreach ($urls as $url) {
                    $video = new Video;
                    $video->setVideo($url);
                    // link last trick with uploading url
                    $video->setTrick($lastTrick);
                    // save video into db
                    $this->entityManager->persist($video);
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

    /**
     * Modify a trick
     */
    #[Route('/modify_trick/{slug}', name: 'modify_trick', methods: ['GET', 'POST'])]
    public function modifyTrick(string $slug, Request $request): Response
    {
        // Get trick info
        $trick = $this->trickRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(ModifyTrickFormType::class, $trick);

        // Get images, and videos
        $images = $trick->getImages();
        $videos = $trick->getVideos();

        // Get groups
        $groups = $this->groupRepository->findAll();

        return $this->render('tricks/update.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos,
            'groups' => $groups,
            'modifyTrickForm' => $form->createView()
        ]);
    }

}