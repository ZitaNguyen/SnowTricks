<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $utils;
    private $entityManager;


    public function __construct(AuthenticationUtils $utils, EntityManagerInterface $entityManager)
    {
        $this->utils = $utils;
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'registration', methods: ['GET', 'POST'])]
    public function register(Request $request, ImageUpload $imageUploadService): Response
    {
        $user = new User;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // upload avatar
            $image = $form->get('avatar')->getData();
            if ($image)
                $user->setImage($imageUploadService->uploadImage($image));
            // save user into db
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre compte est bien créé! Veuillez connecter...');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/registration.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }


    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {

        // get the login error if there is one
        $error = $this->utils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->utils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'lastUsername'  => $lastUsername
        ]);
    }


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


}
