<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $registry;

    public function __construct(private UrlGeneratorInterface $urlGenerator, ManagerRegistry $registry)
    {
        $this->urlGenerator = $urlGenerator;
        $this->registry = $registry;
    }

    /**
     * Authenticate
     * @param Request $request request
     *
     * @return Passport
     * @throws CustomUserMessageAuthenticateException
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        $userRepository = new UserRepository($this->registry);
        $user = $userRepository->findOneBy(['username' => $username]);


        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Le nom d\'utilisateur ne correspond à aucun compte.');
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Veuillez activer votre compte en cliquant sur le lien reçu par mail avant de vous connecter.');
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('_password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    /**
     * Authentication success
     * @param Request $request request
     * @param TokenInterface $token token
     * @param string $firewallName firewall name
     *
     * @return ?Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * Get login url
     * @param Request $request request
     *
     * @return string
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
