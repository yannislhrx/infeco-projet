<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appartement;
use App\Form\AjoutAppartementType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping\Entity;




class AppartementController extends AbstractController
{
    /**
     * @Route("/appartement", name="app_appartement")
     */
    public function index(): Response
    {
        return $this->render('appartement/index.html.twig', [
            'controller_name' => 'AppartementController',
        ]);
    }

    public function getAppartements(): Response {

        // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Appartement::class);
        $appartements = $repository->findAll();

        return $this->render('administration/liste-appartement.html.twig', [
            'appartements' => $appartements,
        ]);
    }

    public function pageAjoutAppartement(): Response {

        // Récupération de tous les produits depuis la base de données
       

        return $this->render('administration/ajout-appartement.html.twig', [
            
        ]);
    }

    public function create(Request $request)
    {
        $entity = new Appartement();
        $form = $this->createForm(AjoutAppartementType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            // dump($entity);die;
            return $this->redirectToRoute('liste-appartement', ['id' => $entity->getId()]);
        }

        return $this->render('administration/ajout-appartement.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(Entity $entity)
    {
        return $this->render('entity/show.html.twig', [
            'entity' => $entity,
        ]);
    }
}
