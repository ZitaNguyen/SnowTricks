<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CommentController extends AbstractController
{
    public function __construct(
        private CommentRepository $commentRepository,
        private TrickRepository $trickRepository,
        private EntityManagerInterface $entityManager
    ){
    }

    /**
     * Add a new comment
     */
    #[Route('/add_comment/{trickID}', name: 'add_comment', methods: ['POST'])]
    public function addComment(int $trickID, Request $request, ValidatorInterface $validator): Response
    {
        // Get trick info
        $trick = $this->trickRepository->findOneBy(['id' => $trickID]);
        $slug = $trick->getSlug();

        // Get the comment data from the form submission
        $commentText = $request->request->get('comment');

        // Create a validation constraint for the comment text
        $constraints = new Assert\Collection([
            'comment' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 5]),
            ],
        ]);

        // Validate the comment data
        $violations = $validator->validate(['comment' => $commentText], $constraints);

        // Check if there are any validation errors
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->addFlash('danger', $violation->getMessage());
            }
        } else {
            // set comment
            $comment = new Comment();
            $comment->setComment($commentText);

            // set user
            $comment->setUser($this->getUser());

            // set trick
            $trick = $this->trickRepository->findOneBy(['id' => $trickID]);
            $comment->setTrick($trick);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('get_trick', ['slug' => $slug]);

    }

}