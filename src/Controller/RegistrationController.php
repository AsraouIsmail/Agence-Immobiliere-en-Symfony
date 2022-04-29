<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine)
    {
        $this->passwordHasher = $passwordHasher;

        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/registration", name="app_registration")
     */
    public function index(Request $request): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //Encoder le nouveau password

            $client->setPassword($this->passwordHasher->hashPassword($client, $client->getPassword()));

            //attribuer les roles

            $client->setRoles(['ROLE_USER']);

            //Enregistrer

            $em = $this->doctrine->getManager();

            $em->persist($client);

            $em->flush();

            // return $this->redirectToRoute('app_client_security');
        }
        return $this->render('registration/index.html.twig', [
            // 'controller_name' => 'RegistrationController',

            'form' => $form->createView(),
        ]);
    }
}
