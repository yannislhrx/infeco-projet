<?php

namespace App\Controller;

use App\Entity\Locataire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LocataireController extends AbstractController
{
    /**
     * @Route("/locataire", name="app_locataire")
     */
    public function index(): Response
    {
        return $this->render('locataire/index.html.twig', [
            'controller_name' => 'LocataireController',
        ]);
    }

    public function getLocataires(): Response {

            // Récupération de tous les produits depuis la base de données
        $repository = $this->getDoctrine()->getRepository(Locataire::class);
        $locataires = $repository->findAll();

        return $this->render('administration/liste-locataire.html.twig', [
            'locataires' => $locataires,
        ]);
    }
}
