<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/register', name: 'registration', methods: ['GET', 'POST'])]
    public function register(Request $request, SluggerInterface $slugger): Response
    {
        $user = new User;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // upload image
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Echec de télécharger votre image.');
                }

                $user->setImage($newFilename);
            }

            // save user
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre compte est bien créé! Veuillez connecter...');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }


    #[Route('/forgot_password', name: 'forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, MailerInterface $mailer): Response
    {
        // Create a form for password reset request
        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            // Generate a token

            // Send an email with a link containing the token
            if ($username == 'zn') { // check username exists in db
                $email = (new Email())
                ->from('zitanguyen84@gmail.com')
                ->to('zitamama84@gmail.com')
                ->subject('Hello, World!')
                ->text('This is the email body.');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('danger', "Echec d\'envoyer email pour réinitialiser votre mot de passe. $e");
                    return $this->redirectToRoute('forget_password');
                }

                // Save the token and expiration timestamp in the database

                // Send a success message to the user
                $this->addFlash('success', 'Un email avec le lien de réinitialisation du mot de passe est envoyé.');

                // Redirect to home page
                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'Votre nom n\'exist pas dans la base de donnée.');
        }

        return $this->render('user/forgot-password.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }


    #[Route('/reset_password/token', name: 'reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Verify the token and expiration timestamp
        if ($user->isPasswordResetTokenValid())
        {
            // If valid, create a form for setting the new password
            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);

            // Handle form submission and update the user's password
            if ($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();

                // hash the password
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);

                // Save the updated user
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                // Send a success message to the user
                $this->addFlash('success', 'Votre nouveau mot de passe est bien enregistré! Veuillez connecter...');

                // Redirect to a login page
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('user/reset-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }

}
