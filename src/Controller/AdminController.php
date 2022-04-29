<?php

namespace App\Controller;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     *  @var PropertyRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
private $em;
     
     public function __construct(PropertyRepository $repository, ManagerRegistry $em) {
        $this->em = $em;
         $this->repository = $repository;
        
     }


    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index(): Response
    {
        $properties = $this->repository->findAll();

        // dump($properties);

        return $this->render('admin/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/admin/property/new", name="admin.property.create")
     */
    public function newProperty(Request $request, ManagerRegistry $em): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);


       if ($form->isSubmitted() && $form->isValid()) {

           $em = $em->getManager();

           $em->persist($property);
           $em->flush();

           return $this->redirectToRoute('admin.property.index');
       }

        return $this->render('admin/edit/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }




    /**
     * @Route("/admin/edit/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     */
    public function edit(Property $property, Request $request, ManagerRegistry $em): Response
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em = $em->getManager();
            $em->flush();
            $this->addFlash('success', 'le Bien a etait modifié avec succès');

            return $this->redirectToRoute('admin.property.index');

            
        }

        return $this->render('admin/edit/_edit_form.hmtl.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    /**
     *
     * @Route("/admin/property/{id}", name="admin.property.delete")
     */
    public function delete(Property $property, Request $request, ManagerRegistry $em): Response
    {

        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {

            $em = $em->getManager();
            $em->remove($property);
            $em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
            
        }

        return $this->redirectToRoute('admin.property.index');
    }

}
