<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $authenticationUtils;
    private $security;

    public function __construct(AuthenticationUtils $authenticationUtils, Security $security)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->security = $security;
    }

    public function login(): Response
    {

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'lastUsername'  => $lastUsername
        ]);
    }


    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


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

        return $this->render('security/forgot-password.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

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

        return $this->render('security/reset-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
