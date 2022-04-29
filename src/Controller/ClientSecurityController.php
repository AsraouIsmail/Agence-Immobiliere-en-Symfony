<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ClientSecurityController extends AbstractController
{
    /**
     * @Route("/client/security", name="app_client_security")
     */
    public function loginClient(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        $hasAccess = $this->isGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        return $this->render('client_security/index.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);

        return $this->redirectToRoute('app_properties');
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
