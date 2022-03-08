<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

     /**
     * @var PropertyRepository
     */
    private $repository;
    private $em;


    public function __construct(PropertyRepository $repository, ManagerRegistry $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/properties", name="app_properties")
     */
    public function index(): Response

    {

       $properties = $this->repository->findAllVisible();
    

       dump($properties);
        

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties',

        ]);
    }

    /**
     * @Route("/property/{slug}-{id<[0-9]+>}", name="app_property_show",requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Property $property, string $slug): Response
    {

        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('app_property_show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
            ], 301);
        }

        return $this->render('property/_show_detail.html.twig',[
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }
}
