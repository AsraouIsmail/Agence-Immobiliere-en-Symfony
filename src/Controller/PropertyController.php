<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use App\Notification\ContactNotification;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{

     /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
    private $em;


    public function __construct(PropertyRepository $repository, ManagerRegistry $em) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/properties", name="app_properties")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response

    {
    
    $search = new PropertySearch();

    $form = $this->createForm(PropertySearchType::class, $search);
    $form->handleRequest($request);
    

    $properties = $paginator->paginate(
        $this->repository->findAllVisibleQuery($search),
        $request->query->getInt('page', 1),
        12
    );
    

       dump($properties);
        

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'current_menu' => 'properties',
            'form' => $form->createView()

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


      /**
     * @Route("/contact", name="app_contact")
     * @param Property $property
     * @return Response
     */
    public function contact(Request $request, ContactNotification $notification): Response
    {
        $contact = new Contact();


        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $notification->notify($contact);

            $this->addFlash('success', 'Votre message a bien été envoyé');

        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
