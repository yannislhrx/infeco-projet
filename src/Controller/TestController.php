<?php

namespace App\Controller;

use App\Entity\Locataire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
          // Récupération du jeton de sécurité courant
        
          $user = '';
 
        $test_var = 'je suis var de tewt';

          // Récupération de tous les produits depuis la base de données
          $repository = $this->getDoctrine()->getRepository(Locataire::class);
          $locataires = $repository->findAll();
        //   $locataires = $repository->findBy(['id_agence' => $user->getId()]);
  
        
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'TestController',
            'test_var' => $test_var,
            'locataires' => $locataires,
            'user' => $user,
        ]);
    }
}
