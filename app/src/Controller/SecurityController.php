<?php
/**
 * Security controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 *
 * Manages the security-related actions such as login and logout.
 */
class SecurityController extends AbstractController
{
    /**
     * Login action.
     *
     * This method handles the login process by rendering the login page
     * and processing login attempts.
     *
     * @param AuthenticationUtils $authenticationUtils Authentication utils
     *
     * @return Response HTTP response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * Logout action.
     *
     * This method handles the logout process.
     * The method body is left empty as it will be intercepted by the firewall.
     *
     * @throws \LogicException Always throws this exception
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
